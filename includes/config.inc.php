<?php
if(!defined("S_INC") || S_INC!==true)die();

header('Content-type: text/html; charset=utf-8');
setlocale(LC_NUMERIC, 'id_ID.UTF8', 'id_ID.UTF-8', 'id_ID.8859-1', 'IND.UTF8', 'IND.UTF-8', 'IND.8859-1', 'en_US.UTF8', 'en_US.UTF-8', 'en_US.8859-1', 'en_US', 'ENG', 'English');
date_default_timezone_set('Europe/Moscow');

set_time_limit(60);

mb_http_input('UTF-8');
mb_http_output('UTF-8');
mb_internal_encoding("UTF-8");

session_start();
#-- тема отображения сайта (папка в папке /tpl/<тема>/<язык>/...)
$_SESSION['_SITE_']['theme'] = 'givena';
#-- текущая языковая версия сайта
$_SESSION['_SITE_']['lang'] = 'ru';

$_SESSION['_SITE_']['is_loc'] = (preg_match('/\.(test|wslocal\.ru|crtr)$/',$_SERVER['SERVER_NAME'])) ? true : false;
if (isset($_REQUEST['back_url']))
{
    $_SESSION['_SITE_']['back_url'] = urldecode($_REQUEST['back_url']);
    if ($_SESSION['_SITE_']['back_url']=="")
        $_SESSION['_SITE_']['back_url'] = '/';
}
elseif (!preg_match('~\/\_ajax~is', $_SERVER['REQUEST_URI']))
{
    #-- убираем системные параметры запроса: admin_action=save ajax=1 cl=_main type=...
    $_SESSION['_SITE_']['back_url'] = preg_replace('~(admin_action=[a-z\_]+|ajax=\d+|cl=[a-z\_]+|type=[a-z\_]+)\&?~is', '', $_SERVER['REQUEST_URI']);
    $_SESSION['_SITE_']['back_url'] = preg_replace('~\?\&*$~', '', $_SESSION['_SITE_']['back_url']);
    $_SESSION['_SITE_']['back_url'] = rtrim(preg_replace('~.+\.{3,4}~i', '/', $_SESSION['_SITE_']['back_url']), '&');
}
$_SESSION['_SITE_']['sign'] = (preg_match('~\?~', $_SERVER['REQUEST_URI'])) ? '&' : '?';


if ($_SESSION['_SITE_']['is_loc'])
{
    error_reporting (E_ALL&~E_NOTICE);
    ini_set( 'display_errors', 1);
}
else
    ini_set( 'display_errors', 0);
//ini_set( 'display_errors', 1);
mb_http_input('UTF-8');
mb_http_output('UTF-8');
mb_internal_encoding("UTF-8");

#-- Для PHPMYADMIN'a или еще для кого не надо вызывать коннект к мускулу.
if (!isset($_SESSION['_SITE_']['no_connecting']) || !$_SESSION['_SITE_']['no_connecting'])
{
    global $_CONN;
    $_CONN = false;
    include_once(S_ROOT . '/includes/db.inc.php');
}
global $pre_pay; 
$pre_pay = 50;
?>