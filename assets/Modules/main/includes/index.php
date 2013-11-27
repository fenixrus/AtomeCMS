<?php
/*COMMENT*/

use \Atome\System;

// View begin ( automatic configure Twig )
$tpl = System::getViewInstance( );

echo 'This is a content of page'; // -=> defined -=> {{ content }}

// View end ( automatic $tpl->display('index.twig'); )
$tpl->execute();