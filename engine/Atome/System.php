<?php
/*COMMENT*/
namespace Atome;

use \Atome\System\Router,
    \Atome\System\Settings,
    \R;

/**
 * Class System - получает объекты контроллера, ОРМ, шаблонизатора...
 * @package Atome
 */
class System
{
    /**
     * Аргумент передаваемый модулю в адресной строке
     * @var null
     */
    public static $argv = null;

    /**
     * Настройки системы из файла /engine/Config/.system.php
     * @var null
     */
    public static $settings = null;

    /**
     * Тип приложения - production | development
     * @var string
     */
    public static $env = 'production';

    /**
     * Возвращает объект контроллера
     * @return Router
     */
    public static function getRouterInstance()
    {
        return new Router();
    }

    /**
     * Возвращает объект шаблонизатора
     * @param null $templatesDir
     * @return \Twig_Environment
     */
    public static function getViewInstance($templatesDir = null)
    {
        require ATOME_ENGINE_DIR . DS . 'Twig' . DS . 'Autoloader.php';
        \Twig_Autoloader::register(true);

        $template = !is_null($templatesDir) ? $templatesDir : static::$settings['default_theme'];
        $templatesDir = ATOME_ASSETS_DIR . DS . 'Themes' . DS . $template;
        $loader = new \Twig_Loader_Filesystem($templatesDir);

        return new \Twig_Environment($loader, array(
            'cache' => $templatesDir . DS . 'cache',
            'auto_reload' => true,
        ));
    }

    /**
     * Устанавливает тип проекта в production или development
     * @param string $env
     */
    public static function setProjectEnviroment($env = 'production')
    {
        static::$env = $env;
        if (static::$env == 'production') {
            error_reporting(0);
        } else {
            error_reporting(E_ALL || E_NOTICE);
        }
    }

    /**
     * Загружает и настраивает Объект ОРМ RedBean
     * @param null $settings
     */
    public static function loadOrmInstance($settings = null)
    {
        require_once ATOME_ENGINE_DIR . DS . 'RedBean' . DS . 'RedBean.php';

        if (is_null($settings)) {
            $database = new Settings('database', false, false);
            $settings = $database->getAll();
        }

        R::autoSetup($settings);
        if (static::$env == ATOME_ENV_PRODUCTION) {
            R::freeze();
        }
    }

    /**
     * Загружает системные настройки
     */
    public static function loadSystemSettings()
    {
        $settings = new Settings('system', false, false);
        static::$settings = $settings->getAll();
    }

    /**
     * Парсит статический шаблон, подставляет переменные из $params и возвращает готовый HTML
     * @param $template Путь к шаблону
     * @param $params Ассоциативный массив переменных
     * @return string Готовый HTML
     * @throws \Exception Не может загрузить шаблон
     */
    public static function getParsedTemplate($template, $params)
    {
        if (!file_exists($template)) {
            throw new \Exception('Can\'t load template ' . $template, 404);
        }
        $template = file_get_contents($template);
        $template = strtr($template, $params);
        return $template;
    }

    /**
     * Хеширует с солью в md5
     * @param null $string
     * @return string md5
     */
    public static function hash($string = null)
    {
        return md5(base64_encode(static::$settings['salt']) . md5($string));
    }
}