<?php
/*COMMENT*/

include '../../engine/init.php';

/*
 * Autoloader register
 */
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