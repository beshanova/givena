<?php
if(!defined("S_INC") || S_INC!==true)die();
#-- Класс модуля для работы с БД. Должен наследовать класс DB, для подключения функций работы с запросами к БД.

class TemplatesSql extends DB
{

    function __construct()
    {
        parent::__construct();
    }

    function getModuleData($id)
    {
        $sql = 'SELECT * FROM ?_module_title, ?_topics_modules WHERE module_title_topic_modules_id=?i AND topics_module_id=module_title_topic_modules_id';
        return $this->query($sql, array($id));
    }

    function saveTitleData($id)
    {
        $sql = 'UPDATE ?_module_title SET module_title_text=?s, module_title_tpl=?s, module_title_date_update=NOW() WHERE module_title_topic_modules_id=?i';
        $this->mysqlquery($sql, array( $_REQUEST['title'], $_REQUEST['template'], $id ));

        $sql = 'UPDATE ?_topics_modules SET topics_module_is_active=?i, topics_module_date_update=NOW() WHERE topics_module_id=?i';
        $this->mysqlquery($sql, array( $_REQUEST['is_active'], $id ));
    }

    function saveNewBlock($id)
    {
        if (trim($_REQUEST['tpl'])=="")
            $_REQUEST['tpl'] = 'template.php';
        $sql = 'INSERT INTO ?_module_title SET module_title_topic_modules_id=?i, module_title_tpl=?s, module_title_date_update=NOW()';
        $this->mysqlquery($sql, array( $id, $_REQUEST['tpl'] ));
    }

    function deleteContentBlock($id)
    {
        $sql = 'DELETE FROM ?_module_title WHERE module_title_topic_modules_id=?i';
        $this->mysqlquery($sql, array( $id ));

        $sql = 'DELETE FROM ?_topics_modules WHERE topics_module_id=?i';
        $this->mysqlquery($sql, array( $id ));
    }

}
?>