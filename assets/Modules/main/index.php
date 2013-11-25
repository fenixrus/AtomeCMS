<?php
/*COMMENT*/

use \Atome\System;

$tpl = System::getViewInstance();

$tpl->addGlobal('content', 'Привет');
$tpl->display('index.twig');