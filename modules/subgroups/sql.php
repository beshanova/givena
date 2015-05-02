<?php
if(!defined("S_INC") || S_INC!==true)die();
#-- Класс модуля для работы с БД. Должен наследовать класс DB, для подключения функций работы с запросами к БД.

class SubGroupsSql extends DB
{

    function __construct()
    {
        parent::__construct();
    }

    function getModuleDetail($id){
        $data = array();
        $sql = 'SELECT * FROM ?_topics, ?_topics_modules WHERE topic_id=?i AND topic_id=topics_module_id_topic';
        $data = $this->query($sql, array($id));
        if (preg_match('~(.+\/)(.+)\.(jpg|jpeg|gif|png|bmp)$~is', $data['page_img'], $ar))
        {
            $data['page_img_data']['dirname'] = $ar[1];
            $data['page_img_data']['name'] = $ar[2] . '.' . $ar[3];
        }
        return $data;
    }

    function getModuleList($id){
        $data = array();
        $sql = 'SELECT * FROM ?_module_menu WHERE module_menu_topic_id=?i';
        $menu_item = $this->query($sql, array($id));

        $menu = new Menu();
        $data = $menu->f_menu_part($menu_item['module_menu_id'], $menu_item['module_menu_type']);

        $sub_data = array();
        foreach ($data as $id_s=>$mas){

            if ($mas['module_menu_parent_id']==$menu_item['module_menu_id']){
                $mas['detail'] = $this->getModuleDetail($mas['module_menu_topic_id']);
                $sub_data[] = $mas;
            }
        }
//        printarray($sub_data);
        return $sub_data;
    }

    function getModuleData($id, $show_childs = 0)
    {
        $sql = 'SELECT * FROM ?_module_title, ?_topics_modules, ?_topics WHERE module_title_topic_modules_id=?i AND topic_id=topics_module_id_topic AND topics_module_id=module_title_topic_modules_id';
        $data = $this->query($sql, array($id));

        if (preg_match('~(.+\/)(.+)\.(jpg|jpeg|gif|png|bmp)$~is', $data['page_img'], $ar))
        {
            $data['page_img_data']['dirname'] = $ar[1];
            $data['page_img_data']['name'] = $ar[2] . '.' . $ar[3];
        }

        if ($show_childs)
            $data['list'] = $this->getModuleList($data['topic_id']);

        return $data;
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