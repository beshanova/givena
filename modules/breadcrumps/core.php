<?php
if(!defined("S_INC") || S_INC!==true)die();

#-- самый первый класс, с которого все запускается. Управляющий класс.

class Breadcrumps extends ExtController
{
    public $sql=null;
    public $tm_id='';
    public $tpl='';
    public $func='';
    public $action='';
    protected $app=null;
    const C_NAME = 'Breadcrumps';
    const C_TITLE = 'Строка навигации';
    const C_DESC = 'Строка навигации';
    const C_ISPUB = 1;

    public function __construct()
    {
        global $APP;
        $this->app = $APP;
        $this->sql = new BreadcrumpsSql();
        $this->action = $_REQUEST['admin_action'];
    }

    #-- Функция возвращает контент
    private function getContent()
    {
        global $APP;
        $data = $this->sql->getModuleData($this->tm_id);
        //printarray($APP->topic);

        $item_id = intval($_REQUEST['id']);
        if ($item_id>0)
        {
            $mod = '';
            #-- ищем каталожный модуль
            foreach ($APP->topic[0]['modules'] as $m)
            {
                if (in_array($m['topics_module_class'], array('catalog', 'gallery', 'news', 'faq')))
                {
                    $q = $this->sql->getCatalogItemData($item_id, $m['topics_module_class']);
                    $q['module_menu_url_full'] = $_SERVER['REQUEST_URI'];
                    $APP->topic[-1] = $q;
                    break;
                }
            }
            ksort($APP->topic);
        }

        return $this->ApplyTemplate($data['module_breadcrumps_tpl'], array('topics'=>$APP->topic), $this->getClassName());
    }

    public function __toString()
    {
        return $this->getContent();
    }

    function saveNewBlock()
    {
        $this->sql->saveNewBlock($this->tm_id);
        $this->app->main_с_saveAdminAction('insert', self::C_TITLE.' (ID:'.$this->tm_id.')', $this->action, 'Добавление блока');
    }

    public function loadPopupAdminBlockEdit()
    {
        $this->actionPopup();
        $data = $this->sql->getModuleData($this->tm_id);
        $tpls = $this->extc_getTemplatesList($this->getClassName());

        print $this->ApplyTemplateAdmin('edit.php', array('data'=>$data, 'tpls'=>$tpls, 'cl'=>$this->getClassName(), 'tm_id'=>$this->tm_id), $this->getClassName());
    }

    private function actionPopup()
    {
        switch ($this->action)
        {
            case 'delete':
                $this->sql->deleteModuleBlock($this->tm_id);
                $this->app->main_с_saveAdminAction('delete', self::C_TITLE.' (ID:'.$this->tm_id.')', $this->action, 'Удаление блока');
                headerTo($_SESSION['_SITE_']['back_url']);
            case 'save':
                $this->sql->saveModuleData($this->tm_id);
                $this->app->main_с_saveAdminAction('update', self::C_TITLE.' (ID:'.$this->tm_id.')', $this->action, 'Изменение настроек блока');
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