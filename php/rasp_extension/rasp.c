/* RASP Inspector */

#ifdef HAVE_CONFIG_H
# include "config.h"
#endif

#include "php.h"
#include "ext/standard/info.h"
#include "php_rasp.h"


static HashTable original_handlers;


const char *inspect_functions[] = {
    "system", "shell_exec", "passthru", "exec", NULL
};


const char *disabled_functions[] = {
    "phpinfo",
    NULL
};

const char *meta_characters[] = {
    ";", "&", "|", "$(", "<", ">", NULL // "\n" and "`" is not included, here is the vulnerability.
};

PHP_FUNCTION(rasp_command_inspector)
{
   
    char *command;
    size_t command_len;
    const char *function_name = get_active_function_name();

    if (zend_parse_parameters(ZEND_NUM_ARGS(), "s", &command, &command_len) == FAILURE) {
        zend_error(E_WARNING, "RASP: Internal Error - Inspector called on function with wrong parameters: %s()", function_name);
        RETURN_NULL();
    }

    for (int i = 0; meta_characters[i] != NULL; i++) {
        if (strstr(command, meta_characters[i]) != NULL) {
            zend_error(E_WARNING, "RASP: [Command Injection] Blocked call to %s(). Malicious character found: %s. Command: \"%s\"", function_name, meta_characters[i], command);
            php_printf("<h1>RASP Detected and Blocked a Command Injection Attempt!</h1>");
            RETURN_NULL();
        }
    }

    

    fprintf(stderr, "RASP: [Safe] Allowed call to %s(). Command: \"%s\"\n", function_name, command);
    zif_handler original_handler = zend_hash_str_find_ptr(&original_handlers, function_name, strlen(function_name));
    if (original_handler) {
        original_handler(INTERNAL_FUNCTION_PARAM_PASSTHRU);
    } else {
        zend_error(E_WARNING, "RASP: Could not find original handler for %s().", function_name);
        RETURN_NULL();
    }
}


PHP_FUNCTION(rasp_info_disclosure_blocker)
{
    
    const char *function_name = get_active_function_name();

    
    zend_error(E_WARNING, "RASP: [Info Disclosure] Disabled sensitive function: %s()", function_name);

    
    php_printf("<h1>RASP: The function %s() has been disabled for security reasons.</h1>", function_name);
    
    
    RETURN_NULL();
}



static void hook_function(const char *func_name, zif_handler new_handler)
{
    size_t func_name_len = strlen(func_name);
    zend_function *original_function = zend_hash_str_find_ptr(CG(function_table), func_name, func_name_len);

    if (original_function) {
        zend_hash_str_add_ptr(&original_handlers, func_name, func_name_len, original_function->internal_function.handler);
        original_function->internal_function.handler = new_handler;
        fprintf(stderr, "RASP: Successfully hooked function '%s' with a new handler.\n", func_name);
    }
}



PHP_MINIT_FUNCTION(rasp)
{
    zend_hash_init(&original_handlers, 8, NULL, NULL, 1);
    fprintf(stderr, "RASP MINIT: Applying security policies...\n");

    
    for (int i = 0; inspect_functions[i] != NULL; i++) {
        hook_function(inspect_functions[i], PHP_FN(rasp_command_inspector));
    }

    
    for (int i = 0; disabled_functions[i] != NULL; i++) {
        hook_function(disabled_functions[i], PHP_FN(rasp_info_disclosure_blocker));
    }

    return SUCCESS;
}



PHP_MSHUTDOWN_FUNCTION(rasp)
{
    zend_string *key;
    void *original_handler;
    fprintf(stderr, "RASP MSHUTDOWN: Restoring original function handlers...\n");
    ZEND_HASH_FOREACH_STR_KEY_PTR(&original_handlers, key, original_handler) {
        if (key) {
            zend_function *function_to_restore = zend_hash_find_ptr(CG(function_table), key);
            if (function_to_restore) {
                function_to_restore->internal_function.handler = original_handler;
            }
        }
    } ZEND_HASH_FOREACH_END();
    zend_hash_destroy(&original_handlers);
    return SUCCESS;
}
PHP_MINFO_FUNCTION(rasp)
{
    php_info_print_table_start();
    php_info_print_table_row(2, "RASP Lab (Multi-Strategy Inspector)", "enabled");
    php_info_print_table_end();
}
zend_module_entry rasp_module_entry = {
    STANDARD_MODULE_HEADER, "rasp", NULL,
    PHP_MINIT(rasp), PHP_MSHUTDOWN(rasp),
    NULL, NULL, PHP_MINFO(rasp), "1.2.0",
    STANDARD_MODULE_PROPERTIES
};

#ifdef COMPILE_DL_RASP
# ifdef ZTS
ZEND_TSRMLS_CACHE_DEFINE()
# endif
ZEND_GET_MODULE(rasp)
#endif
