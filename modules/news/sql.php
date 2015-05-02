<?php
if(!defined("S_INC") || S_INC!==true)die();
#-- Класс модуля для работы с БД. Должен наследовать класс DB, для подключения функций работы с запросами к БД.

class NewsSql extends DB
{

    function __construct()
    {
        parent::__construct();
    }

}
?>