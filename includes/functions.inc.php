<?php
if(!defined("S_INC") || S_INC!==true)die();

function headerTo($url)
{
    if ($url)
    {
        header('Location:'.$url);
        exit;
    }
}

function header_error_404()
{
    header("HTTP/1.1 404 Not Found");
    header("Status: 404 Not Found");
}

if (!function_exists('mb_ucfirst') && function_exists('mb_substr'))
{
    function mb_ucfirst($string)
    {
        $string = mb_ereg_replace("^[\ ]+","", $string);
        $string = mb_strtoupper(mb_substr($string, 0, 1, "UTF-8"), "UTF-8").mb_substr($string, 1, mb_strlen($string), "UTF-8" );
        return $string;
    }
}

if (!function_exists('mb_trim'))
{
    function mb_trim( $string )
    {
        $string = mb_ereg_replace( "/(^\s+)|(\s+$)/ius", "", $string );
        return $string;
    }
}

function parseDate2Sql($dt)
{
    if (preg_match('~(\d{2})\.(\d{2})\.(\d{4})\s?(.+)?~', $dt, $ar))
        $dt = $ar[3].'-'.$ar[2].'-'.$ar[1].($ar['4']!=''?' '.$ar['4']:'');
    elseif (preg_match('~(\d{4})\-(\d{2})\-(\d{2})\s?(.+)?~', $dt, $ar))
        $dt = $dt;
    else
        $dt = '';

    return trim($dt);
}

function dateFormat($sqldate, $time=false)
{
    return date('d.m.Y' . ($time?' H:i':''), strtotime($sqldate));
}

function printarray($array)
{
    echo '<PRE style="color:#000;">';
    print_r($array);
    echo '</PRE>';
}

function get_russian_month($num){
    $months = Array('Января', 'Февраля', 'Марта', 'Апреля', 'Мая', 'Июня', 'Июля', 'Августа', 'Сентября', 'Октября', 'Ноября', 'Декабря');
    return $months[$num-1];
}

function translit($str)
{
    $tr = array(
        "А"=>"a","Б"=>"b","В"=>"v","Г"=>"g",
        "Д"=>"d","Е"=>"e","Ж"=>"j","З"=>"z","И"=>"i",
        "Й"=>"y","К"=>"k","Л"=>"l","М"=>"m","Н"=>"n",
        "О"=>"o","П"=>"p","Р"=>"r","С"=>"s","Т"=>"t",
        "У"=>"u","Ф"=>"f","Х"=>"h","Ц"=>"ts","Ч"=>"ch",
        "Ш"=>"sh","Щ"=>"sch","Ъ"=>"","Ы"=>"yi","Ь"=>"",
        "Э"=>"e","Ю"=>"yu","Я"=>"ya","а"=>"a","б"=>"b",
        "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
        "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
        "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
        "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
        "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
        "ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya",
        " "=> "_"
    );
    $str = strtr($str,$tr);
//    $str = preg_replace('~[^a-z0-9\_\-\.\/\:]~is', '', $str);
    return $str;
}

function cmp_my_sort($a, $b)
{
    return strnatcasecmp($a['sortby'],$b['sortby'])>0 ? 1 : -1;
}

function min_plus($arr){
    $min = current($arr);

    foreach ($arr as $m=>$val){
        if (($val>0) && ($val<$min))
            $min = $val;
    }
    return floatval($min);
}

if (! function_exists('mb_str_split'))
{
    function mb_str_split($str)
    {
        preg_match_all('/.{1}|[^\x00]{1}$/us', $str, $ar);
        return $ar[0];
    }
}

if (! function_exists('mb_strtr'))
{
    function mb_strtr($str, $from, $to)
    {
        return str_replace(mb_str_split($from), mb_str_split($to), $str);
    }
}

function filter_price_mass($var)
{
    return ($var>0);
}