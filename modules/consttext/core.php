<?php
if(!defined("S_INC") || S_INC!==true)die();

#-- самый первый класс, с которого все запускается. Управляющий класс.

class Consttext extends ExtController
{

    protected $sql=null;
    public $tm_id='';
    public $tpl='';
    public $func='';
    public $action='';
    protected $app=null;
    const C_NAME = 'Consttext';
    const C_TITLE = 'Константа';
    const C_DESC = 'Константа';
    const C_ISPUB = 0;

    public function __construct()
    {
        global $APP;
        $this->app = $APP;
        $this->sql = new ConsttextSql();
        $this->action = (isset($_REQUEST['admin_action'])) ? $_REQUEST['admin_action'] : '';
    }

    #-- Функция возвращает контент
    private function getContent()
    {
        $this->sql->getConsttextData($this->tm_id, $this->func);
        return $this->ApplyTemplate(($this->tpl!=""?$this->tpl:'template.php'), array('const'=>$this->sql->data), $this->getClassName());
    }

    public function __toString()
    {
        return $this->getContent();
    }

    public function loadPopupAdminBlockEdit()
    {
        $this->actionPopup();

        $this->sql->getConsttextData($this->tm_id);
        print $this->ApplyTemplateAdmin('edit.php', array('const'=>$this->sql->data, 'cl'=>$this->getClassName(), 'tm_id'=>$this->tm_id), $this->getClassName());
    }

    private function actionPopup()
    {
        switch ($this->action)
        {
            case 'save':
                $this->sql->saveConsttextData($this->tm_id);
                $this->app->main_с_saveAdminAction('update', self::C_TITLE.' (ID:'.$this->tm_id.')', $this->action, 'Изменение содержимого блока');
                headerTo($_SESSION['_SITE_']['back_url']);
            default:
        }
    }

    private function __clone() {}

    public function __destruct() {}

    public function getClassName() { return self::C_NAME; }
    public function getClassTitle() { return self::C_TITLE; }
    public function getClassDesc() { return self::C_DESC; }
    public function getClassIsPub() { return self::C_ISPUB; }

}