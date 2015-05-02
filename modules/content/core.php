<?php
if(!defined("S_INC") || S_INC!==true)die();

#-- самый первый класс, с которого все запускается. Управляющий класс.

class Content extends ExtController
{

    protected $sql=null;
    public $tm_id=0;
    public $action='';
    protected $app=null;
    const C_NAME = 'Content';
    const C_TITLE = 'Контент';
    const C_DESC = 'Контент';
    const C_ISPUB = 1;

    public function __construct()
    {
        global $APP;
        $this->app = $APP;
        $this->sql = new ContentSql();
        $this->action = $_REQUEST['admin_action'];
    }

    #-- Функция возвращает контент
    private function getContent()
    {
        $data = $this->sql->getModuleData($this->tm_id);
        return $this->ApplyTemplate($data['module_content_tpl'], array('content'=>$data['module_content_text'], 'is_active'=>$data['topics_module_is_active']), $this->getClassName());
    }

    public function __toString()
    {
        return $this->getContent();
    }

    public function loadPopupAdminBlockEdit()
    {
        $this->actionPopup();

        $data = $this->sql->getModuleData($this->tm_id);
        $tpls = $this->extc_getTemplatesList($this->getClassName());

        print $this->ApplyTemplateAdmin('edit.php', array('content'=>$data, 'tpls'=>$tpls, 'cl'=>$this->getClassName(), 'tm_id'=>$this->tm_id), $this->getClassName());
    }

    private function actionPopup()
    {
        switch ($this->action)
        {
            case 'delete':
                $this->sql->deleteContentBlock($this->tm_id);
                $this->app->main_с_saveAdminAction('delete', self::C_TITLE.' (ID:'.$this->tm_id.')', $this->action, 'Удаление блока');
                headerTo($_SESSION['_SITE_']['back_url']);
            case 'save':
                $this->sql->saveContentData($this->tm_id);
                $this->app->main_с_saveAdminAction('update', self::C_TITLE.' (ID:'.$this->tm_id.')', $this->action, 'Изменение контента блока');
                headerTo($_SESSION['_SITE_']['back_url']);
            default:
        }
    }

    function saveNewBlock()
    {
        $this->sql->saveNewBlock($this->tm_id);
        $this->app->main_с_saveAdminAction('insert', self::C_TITLE.' (ID:'.$this->tm_id.')', $this->action, 'Добавление блока');
    }

    private function __clone() {}

    public function __destruct() {}

    public function getClassName() { return self::C_NAME; }
    public function getClassTitle() { return self::C_TITLE; }
    public function getClassDesc() { return self::C_DESC; }
    public function getClassIsPub() { return self::C_ISPUB; }

    #-- данную функцию использует модуль поиска
    public function searchContentModule($tm_ids, $q_arr)
    {
        return $this->sql->searchContentModule($tm_ids, $q_arr);
    }

}