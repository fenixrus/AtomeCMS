<?php
/*COMMENT*/

use \Atome\System;

// View begin ( configure Twig )
$tpl = System::getViewInstance(null);

echo 'Test'; // {{ content }}

// View end ( $tpl->display('index.twig'); )
$tpl->execute();