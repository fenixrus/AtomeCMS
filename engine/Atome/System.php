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
     * Возвращает объект роутера
     * @param null $routPath
     *
     * @return Router
     */
    public static function getRouterInstance($routPath = null)
    {
        return new Router($routPath = null);
    }

    /**
     * Возвращает объект шаблонизатора Twig
     * @param null $templatesDir Папка с шаблонами
     * @param null $templatesUri URI папки с шаблонами
     * @param bool $useBuffer Использовать ли буфферизацию
     *
     * @return \Twig_Environment
     */
    public static function getViewInstance($templatesDir = null, $templatesUri = null, $useBuffer = true)
    {
        require ATOME_ENGINE_DIR . DS . 'Twig' . DS . 'Autoloader.php';
        \Twig_Autoloader::register(true);

        $templatesUri = $templatesUri ? : ROOT_THEMES . '/' . static::$settings['default_theme'];
        $template = $templatesDir ? : ATOME_THEMES_DIR . DS . static::$settings['default_theme'];
        $loader = new \Twig_Loader_Filesystem($template);
        $tpl = new \Twig_Environment(
            $loader,
            array(
                 'cache' => $template . DS . '_cache',
                 'auto_reload' => true,
            )
        );
        $tpl->addGlobal('root', $templatesUri);

        if ( $useBuffer ) {
            ob_start();
        }
        return $tpl;
    }

    /**
     * Устанавливает тип проекта в production или development
     * @param string $env
     * @return void
     */
    public static function setProjectEnvironment($env = ATOME_ENV_PRODUCTION)
    {
        static::$env = $env;
        if (static::$env == ATOME_ENV_PRODUCTION) {
            error_reporting(0);
        } else {
            error_reporting(E_ALL || E_NOTICE);
        }
    }

    /**
     * Загружает и настраивает Объект ОРМ RedBean
     * @param null $settings
     * @return void
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
     * @return void
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

    /**
     * Вычисляет время отработки скриптов на месте вызова
     * @param int $round степень округления
     * @return float время генерации
     */
    public static function generation($round = 4) {
        return round( microtime(true) - START, $round );
    }
}