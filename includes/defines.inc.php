<?php
if(!defined("S_INC") || S_INC!==true)die();

#-- корень сервера
define('S_ROOT', $S_ROOT);
define('S_HOST', trim(preg_replace('~^http:\/\/~is', '', $_SERVER['SERVER_NAME']), '/'));
define('N', "\r\n");
define('BR', "<br />");

define('PR1', "Голый корень");
define('PR2', "Растение в пакете");
define('PR3', "Цена");
define('PR4', "Растение в контейнере");