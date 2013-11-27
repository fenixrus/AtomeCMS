<?php
/*COMMENT*/

namespace Atome\Module;


class DefaultReceiver {
    /**
     * Файл не найден
     */
    const ERROR_NOT_FOUND = 404;

    /**
     * @var string Папка с файлами страниц
     */
    protected static $includesDir = 'includes';

    /**
     * @param string $uri аргумент передаваемый роутером
     */
    function __construct($uri = 'index') {
        $data = explode( '/', $uri, 2 );
        $page = urldecode( $data[0] );

        if ( isset($data[1]) ) {
            $_GET['argv'] = $data[1];
        }

        $this->_runPage($page);
    }

    /**
     * @param $page Файл, который нужно подключить
     *
     * @throws \Exception Файл не найден
     */
    protected function _runPage($page) {
        $page = __MODULE__ . DS . static::$includesDir . DS . $page . '.php';
        if ( file_exists($page) ) {
            require $page;
        } else {
            throw new \Exception( 'File not found in ' . $page, static::ERROR_NOT_FOUND );
        }
    }
}