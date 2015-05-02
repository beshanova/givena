<?php
if(!defined("S_INC") || S_INC!==true)die();
#-- Класс модуля для работы с БД. Должен наследовать класс DB, для подключения функций работы с запросами к БД.

class ConsttextSql extends DB
{

    public $data = array();

    function __construct()
    {
        parent::__construct();
    }

    function getConsttextData($type, $text=-1)
    {
        $text = ($text!="") ? $text : 'Константа';

        $sql = 'SELECT * FROM ?_module_consttext WHERE module_consttext_type=?s';
        $this->data = $this->query($sql, array($type));
        if (empty($this->data))
        {
            $sql = 'INSERT INTO ?_module_consttext SET module_consttext_type=?s, module_consttext_title=?s';
            $this->mysqlquery($sql, array($type, $text));
        }
        elseif ($this->data['module_consttext_title'] != $text && $text!=-1)
        {
            $sql = 'UPDATE ?_module_consttext SET module_consttext_title=?s WHERE module_consttext_type=?s';
            $this->mysqlquery($sql, array($text, $type));
        }
    }

    function saveConsttextData($type)
    {
        $sql = 'UPDATE ?_module_consttext SET module_consttext_text=?s, module_consttext_date_update=NOW() WHERE module_consttext_type=?s';
        $this->mysqlquery($sql, array(trim($_REQUEST['constant']), $type));
    }

}
?>