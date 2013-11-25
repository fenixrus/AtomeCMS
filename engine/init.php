<?php
/*COMMENT*/

define('START', microtime(true));
define('ATOME', 5.0);
define('DS', DIRECTORY_SEPARATOR);

define('ATOME_ROOT_DIR', dirname(__FILE__));
define('ATOME_ENGINE_DIR', ATOME_ROOT_DIR . DS . 'engine');
define('ATOME_ASSETS_DIR', ATOME_ROOT_DIR . DS . 'assets');

define('ATOME_CONFIG_DIR', ATOME_ENGINE_DIR . DS . 'Config');
define('ATOME_VENDOR_DIR', ATOME_ENGINE_DIR . DS . 'Atome');
define('ATOME_TEMP_DIR', ATOME_ENGINE_DIR . DS . 'Temp');

define('ROOT_HOST', $_SERVER['SERVER_NAME']);
define('ROOT_PORT', $_SERVER['SERVER_PORT']);
define('ROOT_HTTPS', !empty($_SERVER['HTTPS']));

define('ATOME_ENV_PRODUCTION', 'production');
define('ATOME_ENV_DEVELOPMENT', 'development');

define('ATOME_ORM_DRIVER_MYSQL', 'mysql');
define('ATOME_ORM_DRIVER_POSTGRESQL', 'pgsql');
define('ATOME_ORM_DRIVER_SQLITE', 'sqlite');