<?php
/*COMMENT*/

require './engine/init.php';

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
    } elseif ( \Atome\System::$env == ATOME_ENV_DEVELOPMENT ) {
        \Atome\Debug::show('Can\'t load class ' . $className, __LINE__, __FILE__, 404, 'Function autoload');
    }
}

/*
 * Core init
 */
use Atome\System,
    Atome\Debug;

try {
    System::setProjectEnvironment(ATOME_ENV_DEVELOPMENT);
    System::loadSystemSettings();
} catch (Exception $error) {
    new Debug($error);
}

/*
 * Controller init
 */
try {
    $router = System::getRouterInstance();
    $router->run( System::$settings['default_module'] );
} catch (Exception $error) {
    Debug::errorPage($error->getCode());
}

echo '<p style="background: blue;color: white;text-align: center; padding: 5px;">' . System::generation(3) . '</p>';