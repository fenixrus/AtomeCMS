<?php
/*COMMENT*/
namespace Atome\System;

use Atome\System;

/**
 * Class Controller - парсит URI текущей страницы, и направляет на требуемый модуль автоматически
 * @package Atome\System
 */
class Router
{
    /**
     * Модуль не найден
     */
    const ERROR_NOT_FOUND = 404;

    /**
     * Получает путь к модулю по REQUEST_URI или по $defaultUrl
     * @param string $defaultUrl
     * @return null|string
     * @throws \Exception Файл не найден
     */
    public static function getRoutePath($defaultUrl = '/main/index')
    {
        static::_parseGet();
        $route = null;
        $url = static::_parseUrl($_SERVER['REQUEST_URI']);

        if (($route = static::_getWayToModule($url)) != null) {
            return $route;
        } elseif (($route = static::_getWayToMain($url, $defaultUrl)) != null) {
            return $route;
        }

        throw new \Exception('File not found', static::ERROR_NOT_FOUND);
    }

    /**
     * Парсит ключ-значение после знака "?" и изменяет переменную $_GET
     */
    private static function _parseGet()
    {
        $queryString = explode('&', $_SERVER['QUERY_STRING']);
        if (!empty($queryString)) {
            foreach ($queryString as $param) {
                list($key, $value) = explode('=', $param);
                if (isset($key) && isset($value)) {
                    $_GET[$key] = $value;
                }
            }
        }
    }

    /**
     * Парсит url для роутера
     * @param $url
     * @return array|string
     */
    private static function _parseUrl($url)
    {
        $url = $url[0] == '/' ? substr($url, 1) : $url;
        $url = explode('?', $url);
        $url = explode('/', reset($url));
        return $url;
    }

    /**
     * Получает путь к модулю по умолчанию
     * @param $url
     * @param $defaultUrl
     * @return null|string
     */
    private static function _getWayToMain($url, $defaultUrl)
    {
        if (reset($url) == null) {
            $defaultUrl = static::_parseUrl($defaultUrl);
            return static::_getWayToModule($defaultUrl);
        }

        list($page, $argument) = $url;
        $route = ATOME_ASSETS_DIR . DS . 'Modules' . DS . 'main' . DS . $page . '.php';
        if (file_exists($route)) {
            System::$argv = $argument;
            return $route;
        }
        return null;
    }

    /**
     * Получает путь к указанному в REQUEST_URI модулю
     * @param $url
     * @return null|string
     */
    private static function _getWayToModule($url)
    {
        list($module, $page, $argument) = $url;
        $routeFull = ATOME_ASSETS_DIR . DS . 'Modules' . DS . $module . DS . $page . '.php';
        if (file_exists($routeFull)) {
            System::$argv = $argument;
            return $routeFull;
        }
        return null;
    }
}