<?php
if(!defined("S_INC") || S_INC!==true)die();

#-- Класс работы с Каталогом

class Image extends ExtController
{

    protected $sql=null;
    public $tm_id=0;
    public $action='';
    protected $app=null;
    const C_NAME = 'Image';
    const C_TITLE = 'Картинка';
    const C_DESC = 'Картинка';
    #-- в этом модуле данная переменная не должна быть константой, т.к. в зависимости от размещения на странице может меняться
    private $C_ISPUB = 1;

    public function __construct()
    {
        global $APP;
        $this->app = $APP;
        $this->sql = new ImageSql();
        $this->action = (isset($_REQUEST['admin_action'])) ? $_REQUEST['admin_action'] : '';
    }

    #-- Функция возвращает контент
    private function getContent()
    {
        $data = $this->sql->getModuleData($this->tm_id);

        return $this->ApplyTemplate($data['module_image_tpl'], array('image'=>$data, 'is_active'=>$data['topics_module_is_active']), $this->getClassName());
    }

    public function __toString()
    {
        if ($this->func && is_callable(self::C_NAME, $this->func))
        {
            eval('$cont = $this->'.$this->func.';');
            return $cont;
        }
        else
            return $this->getContent();
    }

    public function loadPopupAdminBlockEdit()
    {
        $this->actionPopup();

        $data = $this->sql->getModuleData($this->tm_id);
        $tpls = $this->extc_getTemplatesList($this->getClassName());

        print $this->ApplyTemplateAdmin('edit.php', array('image'=>$data, 'tpls'=>$tpls, 'cl'=>$this->getClassName(), 'tm_id'=>$this->tm_id), $this->getClassName());
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
                $this->sql->saveImageData($this->tm_id);
                $this->app->main_с_saveAdminAction('update', self::C_TITLE.' (ID:'.$this->tm_id.')', $this->action, 'Изменение настроек блока/добавление элемента');
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
    public function getClassIsPub() { return $this->C_ISPUB; }

}