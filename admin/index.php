<?php
session_start();

if (isset($_REQUEST['logout']))
{
    unset($_SESSION['_SITE_']['is_adm']);
    unset($_SESSION['_SITE_']['userdata']);
    unset($_SESSION['_SITE_']['_User']);
    unset($_SERVER['PHP_AUTH_USER']);
    unset($_SERVER['PHP_AUTH_PW']);
    unset($_SERVER['AUTH_TYPE']);

    $_COOKIE['user_l'] = $_COOKIE['user_p'] = '';
    setcookie('user_l', '', time()-666, '/');
    setcookie('user_p', '', time()-666, '/');
    unset($_COOKIE['user_l']);
    unset($_COOKIE['user_p']);
}
elseif ($_SESSION['_SITE_']['is_adm'] != 1 && !isset($_REQUEST['admin_action']))
{
    $_SESSION['_SITE_']['is_form_auth'] = 1;
}

$url = ( isset($_SESSION['_SITE_']['back_url']) && $_SESSION['_SITE_']['back_url']!='' && !preg_match('~^/?admin/?~i', $_SESSION['_SITE_']['back_url'] ) ? $_SESSION['_SITE_']['back_url'] : '/' );

header('Location:'.$url);
exit;
?>