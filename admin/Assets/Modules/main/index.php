<?php
/*COMMENT*/

// Подключаем класс системы
use Atome\System;

// Получаем объект Twig
$view = System::getViewInstance(ATOME_ADMIN_THEME_DIR);

// Контент....
echo 'adminka';
// ...

// Выводим документ
$view->execute();