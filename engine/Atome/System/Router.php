<?php
/*COMMENT*/
namespace Atome\System;

use Atome\System;

/**
 * Class Router - парсит URI текущей страницы, и направляет на требуемый модуль автоматически
 * @package Atome\System
 */
class Router
{
    /**
     * Файл не найден
     */
    const ERROR_NOT_FOUND = 404;

    /**
     * @var string путь к папке с модулями
     */
    private $_routPath = ATOME_ASSETS_DIR;

    /**
     * @var array Отфильтрованные данные о пути
     */
    private $_urlData;

    /**
     * @param null $routPath путь к папке с модулями
     */
    function __construct($routPath = null) {
        $this->_routPath = $routPath ? : ATOME_ASSETS_DIR . DS . 'Modules';
        $this->_urlData = $this->_filterPath();
        $this->_parseGet();
    }

    /**
     * Ищет запрашиваемый модуль исходя из REQUEST_URI
     * @param string $defaultModule Путь роутинга по умолчанию к модели модуля
     * @return void
     * @throws \Exception Файл не найден
     */
    public function run($defaultModule = 'main')
    {
        $data = explode('/', $this->_urlData['path'], 2);
        $module = isset($data[0]) && $data[0] ? $data[0] : $defaultModule;
        $uri = isset($data[1]) && $data[1] ? $data[1] : null;

        $receiver = $this->_routPath . DS . $module . DS . 'Receiver.php';
        if ( file_exists($receiver) ) {
            define('__MODULE__', dirname($receiver));
            require $receiver;

            if ( is_null($uri) ) {
                $object = new \Receiver( );
            } else {
                $object = new \Receiver( $uri );
            }

            return;
        }

        throw new \Exception('File not found in ' . $receiver, static::ERROR_NOT_FOUND);
    }

    /**
     * Парсит QUERY_STRING и заносит в $_GET
     * @return void
     */
    private function _parseGet()
    {
        if (!isset($this->_urlData['query'])) {
            return;
        }

        $get = explode('&', $this->_urlData['query']);
        foreach ($get as $p) {
            list($var, $val) = explode('=', $p);
            $_GET[$var] = $val;
        }
    }

    /**
     * Фильтрует REQUEST_URI и возаращает массив данных
     * @return array Отфильтрованные данные
     */
    private function _filterPath()
    {
        $path = $_SERVER['REQUEST_URI'];
        $path = str_ireplace('/admin', '', $path);
        $path = $path[0] == '/' ? substr($path, 1) : $path;
        return parse_url($path);
    }
}