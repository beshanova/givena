<?php
if(!defined("S_INC") || S_INC!==true)die();
#-- Класс модуля для работы с БД. Должен наследовать класс DB, для подключения функций работы с запросами к БД.

class VideoSql extends DB
{    

    function __construct()
    {
        parent::__construct();
    }

    function getModuleData($id)
    {
        $sql = 'SELECT * FROM ?_module_video, ?_topics_modules WHERE module_content_topic_modules_id=?i AND topics_module_id=module_content_topic_modules_id';
        return $this->query($sql, array($id));
    }

    function saveContentData($id)
    {
        #-- очищаем контент от ненужных тегов форматирования
        $content = $_REQUEST['content'];   

        $sql = 'UPDATE ?_module_video SET module_content_text=?s, module_content_tpl=?s, module_content_date_update=NOW() WHERE module_content_topic_modules_id=?i';
        $this->mysqlquery($sql, array( $content, $_REQUEST['template'], $id ));

        $sql = 'UPDATE ?_topics_modules SET topics_module_is_active=?i, topics_module_date_update=NOW() WHERE topics_module_id=?i';
        $this->mysqlquery($sql, array( $_REQUEST['is_active'], $id ));
    }

    function saveNewBlock($id)
    {
        if (trim($_REQUEST['tpl'])=="")
            $_REQUEST['tpl'] = 'template.php';
        $sql = 'INSERT INTO ?_module_video SET module_content_topic_modules_id=?i, module_content_tpl=?s, module_content_date_update=NOW()';
        $this->mysqlquery($sql, array( $id, $_REQUEST['tpl'] ));
    }

    function deleteContentBlock($id)
    {
        $sql = 'DELETE FROM ?_module_video WHERE module_content_topic_modules_id=?i';
        $this->mysqlquery($sql, array( $id ));

        $sql = 'DELETE FROM ?_topics_modules WHERE topics_module_id=?i';
        $this->mysqlquery($sql, array( $id ));
    }

    #-- функция поиска по модулю удовлетворяющих записей --
    #-- возвращает данные в формате:
    #--  [ключем массива результата является ID модуля] =>
    #--   [content] - найденная строка
    #--   [date_update]    - дата и время последнего изменения найденной информации (записи)
    #--   [date_update_format]    - дата и время последнего изменения найденной информации (записи) в человеко-понятном представлении
    public function searchContentModule($tm_ids, $q_arr)
    {
        $sql_arr = array();
        $sql_inl = '';
        foreach ($q_arr as $a)
        {
            $sql_inl .= ' AND module_content_text LIKE ?s';
            $sql_arr[] = '%'.$a.'%';
        }

        $tm_arr = implode(',', $tm_ids);
        $sql = 'SELECT module_content_topic_modules_id as tm_id, module_content_text as `content`, UNIX_TIMESTAMP(module_content_date_update) as date_update, module_content_date_update as date_update_format FROM ?_module_content WHERE module_content_topic_modules_id IN ('.$tm_arr.') '.$sql_inl;
        $res = $this->mysqlquery($sql, $sql_arr);
        while ($r = $this->fetchOne($res))
        {
            $datas[$r['tm_id']] = $r;
            unset($datas[$r['tm_id']]['tm_id']);
        }
        return $datas;
    }

}
?>