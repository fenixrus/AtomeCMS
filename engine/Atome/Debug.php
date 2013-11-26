<?php
/*COMMENT*/
namespace Atome;

use \Atome\System;

/**
 * Class Debug - используется для отладки системы и отображения объектов типа Exception
 * @package Atome
 */
class Debug
{
    /**
     * Выводит информацию об ошибке
     * @param \Exception $exception
     */
    function __construct(\Exception $exception)
    {
        if (ob_get_contents() != false) {
            ob_end_clean();
        }
        echo System::getParsedTemplate(
            ATOME_ENGINE_DIR . DS . 'Templates' . DS . 'debug.tpl',
            array(
                '{message}' => $exception->getMessage(),
                '{line}' => $exception->getLine(),
                '{file}' => $exception->getFile(),
                '{code}' => $exception->getCode(),
                '{trace}' => $exception->getTraceAsString(),
            )
        );
        die;
    }

    /**
     * Выводит информацию по заданной ошибке
     * @param $message
     * @param $code
     */
    public static function show($message, $code)
    {
        if (ob_get_contents() != false) {
            ob_end_clean();
        }
        echo System::getParsedTemplate(
            ATOME_ENGINE_DIR . DS . 'Templates' . DS . 'error.tpl',
            array(
                '{message}' => $message,
                '{code}' => $code,
            )
        );
        die;
    }

    public static function errorPage($code = 404) {
        header('Location: /assets/Errors/' . $code . '.html');
    }
} 