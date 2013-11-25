<?php
/*COMMENT*/
namespace Atome\System;

/**
 * Class Settings - позволяет работать с файлами настроек
 * @package Atome\System
 */
class Settings
{
    /**
     * Константа ошибки
     */
    const ERROR_FILE_NOT_FOUND = 404;

    /**
     * Автоматически сохранять настройки по завершению работы
     * @var bool
     */
    private $_autoSave = true;

    /**
     * Путь к файлу настроек
     * @var string
     */
    private $_file;

    /**
     * Настройки полученные из файла
     * @var array|null
     */
    private $_settings = null;

    /**
     * @param string $file Название файла в папке Config без ".php" вконце
     * @param bool $createIfNotExists Создать файл если не существует
     * @param bool $autoSave Автоматически сохранять настройки после завершения работы с объектом
     * @throws \Exception Файл не найден
     */
    function __construct($file, $createIfNotExists = true, $autoSave = true)
    {
        $this->_autoSave = $autoSave;
        $this->_file = ATOME_CONFIG_DIR . DS . $file . '.php';

        if (!$createIfNotExists && !file_exists($this->_file)) {
            throw new \Exception('File ' . $this->_file . ' not found', static::ERROR_FILE_NOT_FOUND);
        }

        $config = array();
        require $this->_file;
        $this->_settings = $config;
    }

    /**
     * Сохраняет настройки
     */
    function __destruct()
    {
        if ($this->_autoSave) {
            $this->save();
        }
    }

    /**
     * Возвращает определенну настройку из файла
     * @param $name Имя переменной настроек
     * @return null|mixed
     */
    public function get($name)
    {
        if (isset($this->_settings[$name])) {
            return $this->_settings[$name];
        }
        return null;
    }

    /**
     * Возвращает все настройки
     * @return array|null
     */
    public function getAll()
    {
        return $this->_settings;
    }

    /**
     * Изменяет настройки в переменной, но не сохраняет их
     * @param $name
     * @param string $value
     */
    public function set($name, $value = '')
    {
        $this->_settings[$name] = $value;
    }

    /**
     * Удаляет настройку по имени
     * @param $name
     */
    public function remove($name)
    {
        if (isset($this->_settings[$name])) {
            unset($this->_settings[$name]);
        }
    }

    /**
     * Сохраняет настройки
     * @return bool
     */
    public function save()
    {
        $contents = '<?php' . PHP_EOL;
        $contents .= '$config = ' . var_export($this->_settings, true) . ';';
        return (bool)file_put_contents($this->_file, $contents);
    }
} 