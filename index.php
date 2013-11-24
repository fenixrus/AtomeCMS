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

spl_autoload_register('__autoload');
function __autoload($className)
{
    $className = str_replace('\\', DS, $className) . '.php';
    $classPath = ATOME_ENGINE_DIR . DS;
    if (file_exists($classPath . $className)) {
        require $classPath . $className;
    } elseif (\Atome\System::$env == ATOME_ENV_DEVELOPMENT) {
        \Atome\Debug::show('Can\'t load class ' . $className, __LINE__, __FILE__, 404, 'Function autoload');
    }
}

/*
 * Core init
 */
use Atome\System,
    Atome\Debug;

System::loadSystemSettings();
System::setProjectEnviroment(ATOME_ENV_DEVELOPMENT);

/*
 * Controller init
 */
try {
    $controller = System::getRouterInstance();
    $route = $controller->getRoutePath(System::$settings['default_route']);
    define('__MODULE__', dirname($route));
    unset($controller);
    require $route;
} catch (Exception $error) {
    new Debug($error);
}

echo round(microtime(true) - START, 3);