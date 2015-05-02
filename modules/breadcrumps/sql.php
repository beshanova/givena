<?php
if(!defined("S_INC") || S_INC!==true)die();
#-- Класс модуля для работы с БД. Должен наследовать класс DB, для подключения функций работы с запросами к БД.

class BreadcrumpsSql extends DB
{

    public $data = array();

    function __construct()
    {
        parent::__construct();
    }

    function getModuleData($id)
    {
        $sql = 'SELECT * FROM ?_module_breadcrumps, ?_topics_modules, ?_module_menu WHERE module_breadcrumps_topic_modules_id=?i AND topics_module_id=module_breadcrumps_topic_modules_id AND topics_module_id_topic=module_menu_topic_id';
        return $this->query($sql, array($id));
    }

    function saveModuleData($id)
    {
        if (trim($_REQUEST['tpl'])=="")
            $_REQUEST['tpl'] = 'template.php';
        $sql = 'UPDATE ?_module_breadcrumps SET module_breadcrumps_tpl=?s, module_breadcrumps_date_update=NOW() WHERE module_breadcrumps_topic_modules_id=?i';
        $this->mysqlquery($sql, array($_REQUEST['tpl'], $id));

        $sql = 'UPDATE ?_topics_modules SET topics_module_is_active=?i, topics_module_date_update=NOW() WHERE topics_module_id=?i';
        $this->mysqlquery($sql, array( $_REQUEST['is_active'], $id ));
    }

    function deleteModuleBlock($id)
    {
        $sql = 'DELETE FROM ?_module_breadcrumps WHERE module_breadcrumps_topic_modules_id=?i';
        $this->mysqlquery($sql, array( $id ));

        $sql = 'DELETE FROM ?_topics_modules WHERE topics_module_id=?i';
        $this->mysqlquery($sql, array( $id ));
    }

    function saveNewBlock($id)
    {
        if (trim($_REQUEST['tpl'])=="")
            $_REQUEST['tpl'] = 'template.php';
        $sql = 'INSERT INTO ?_module_breadcrumps SET module_breadcrumps_topic_modules_id=?i, module_breadcrumps_tpl=?s, module_breadcrumps_date_update=NOW()';
        $this->mysqlquery($sql, array( $id, $_REQUEST['tpl'] ));
    }

    function getCatalogItemData($id, $mtype)
    {
        return $this->query('SELECT * FROM ?_module_c_'.$mtype.'_items WHERE module_list_item_id=?i', array($id));
    }

}
?>