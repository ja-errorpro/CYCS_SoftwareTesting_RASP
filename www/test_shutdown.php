<?php 

register_shutdown_function(function() { 
    system('echo Shutdown'); 
}); 

?>
