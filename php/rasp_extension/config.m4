PHP_ARG_ENABLE(rasp, whether to enable RASP protection,
[ --enable-rasp   Enable RASP protection])

if test "$PHP_RASP" = "yes"; then
  AC_DEFINE(HAVE_RASP, 1, [Whether RASP is enabled])
  PHP_NEW_EXTENSION(rasp, rasp.c, $ext_shared)
fi
