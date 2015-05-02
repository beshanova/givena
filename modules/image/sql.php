<?php
if(!defined("S_INC") || S_INC!==true)die();
#-- Класс модуля для работы с БД. Должен наследовать класс DB, для подключения функций работы с запросами к БД.

class ImageSql extends DB
{

    function __construct()
    {
        parent::__construct();
    }

    function getModuleData($id)
    {
        $sql = 'SELECT * FROM ?_module_image, ?_topics_modules, ?_module_menu WHERE module_image_topic_modules_id=?i AND topics_module_id=module_image_topic_modules_id AND topics_module_id_topic=module_menu_topic_id';
        $data = $this->query($sql, array($id));
        if (preg_match('~(.+\/)(.+)\.(jpg|jpeg|gif|png|bmp)$~is', $data['module_image_src'], $ar))
        {
            $data['module_image_src_data']['dirname'] = $ar[1];
            $data['module_image_src_data']['name'] = $ar[2] . '.' . $ar[3];
        }
        return $data;
    }

    function saveImageData($id)
    {
        $n = $_REQUEST['image'];

        if (isset($_FILES['file']) && $_FILES['file']['error']==0)
        {
            $file = new Upload();
            $file->u_dfile = $_FILES['file'];
            $f_path = $file->u_loading();

            $sql = 'UPDATE ?_module_image SET module_image_title=?s, module_image_link=?s, module_image_src=?s, module_image_target=?s, module_image_is_popup=?i, module_image_tpl=?s, module_image_date_update=NOW() WHERE module_image_topic_modules_id=?i';
            $this->mysqlquery($sql, array( $n['title'], $n['link'], $f_path, $n['target'], $n['is_popup'], $_REQUEST['tpl'], $id ));
        }
        else
        {
            $sql = 'UPDATE ?_module_image SET module_image_title=?s, module_image_link=?s, module_image_target=?s, module_image_is_popup=?i, module_image_tpl=?s, module_image_date_update=NOW() WHERE module_image_topic_modules_id=?i';
            $this->mysqlquery($sql, array( $n['title'], $n['link'], $n['target'], $n['is_popup'], $_REQUEST['tpl'], $id ));
        }

        $sql = 'UPDATE ?_topics_modules SET topics_module_is_active=?i, topics_module_date_update=NOW() WHERE topics_module_id=?i';
        $this->mysqlquery($sql, array( $_REQUEST['is_active'], $id ));
    }

    function saveNewBlock($id)
    {
        if (trim($_REQUEST['tpl'])=="")
            $_REQUEST['tpl'] = 'template.php';
        $sql = 'INSERT INTO ?_module_image SET module_image_topic_modules_id=?i, module_image_date_update=NOW()';
        $this->mysqlquery($sql, array( $id ));
    }

    function deleteContentBlock($id)
    {
        $sql = 'DELETE FROM ?_module_image WHERE module_image_topic_modules_id=?i';
        $this->mysqlquery($sql, array( $id ));

        $sql = 'DELETE FROM ?_topics_modules WHERE topics_module_id=?i';
        $this->mysqlquery($sql, array( $id ));
    }

}
?>