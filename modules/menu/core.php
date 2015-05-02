<?php
if(!defined("S_INC") || S_INC!==true)die();

#-- самый первый класс, с которого все запускается. Управляющий класс.

class Menu extends ExtController
{

    protected $sql=null;
    public $tm_id='';
    public $tpl='';
    public $func='';
    public $action='';
    protected $app=null;
    const C_NAME = 'Menu';
    const C_TITLE = 'Меню';
    const C_DESC = 'Меню';
    const C_ISPUB = 0;

    public function __construct()
    {
        global $APP;
        $this->app = $APP;
        $this->sql = new MenuSql();
        $this->action = (isset($_REQUEST['admin_action'])) ? $_REQUEST['admin_action'] : '';
    }

    #-- Функция возвращает контент
    private function getContent()
    {
        $this->sql->getMenuItems($this->tm_id);
        return $this->ApplyTemplate(($this->tpl!=""?$this->tpl:'template.php'), array('menu'=>$this->sql->cur_menu), $this->getClassName());
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

        $glob_max_id = $this->sql->getMaxIdMenu();
        $this->sql->getMenuItems($this->tm_id);
        print $this->ApplyTemplateAdmin('edit.php', array('menu'=>$this->sql->cur_menu, 'glob_max_id'=>$glob_max_id, 'cl'=>$this->getClassName(), 'tm_id'=>$this->tm_id), $this->getClassName());
    }

    private function actionPopup()
    {
        switch ($this->action)
        {
            case 'save_w':
              $this->sql->saveMenuItems($this->tm_id);
              $this->app->main_с_saveAdminAction('update', self::C_TITLE.' (ID:'.$this->tm_id.')', $this->action, 'Изменение блока меню');

              $glob_max_id = $this->sql->getMaxIdMenu();
              $this->sql->getMenuItems($this->tm_id);
              print $this->ApplyTemplateAdmin('edit.php', array('menu'=>$this->sql->cur_menu, 'glob_max_id'=>$glob_max_id, 'cl'=>$this->getClassName(), 'tm_id'=>$this->tm_id), $this->getClassName());
              exit;

            case 'save':
              $this->sql->saveMenuItems($this->tm_id);
              $this->app->main_с_saveAdminAction('update', self::C_TITLE.' (ID:'.$this->tm_id.')', $this->action, 'Изменение блока меню');
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

    #-----------------------------------------------------------------------------
    #-- Дополнительные функции класса, которые могут быть вызваны прямо из шаблона
    #-----------------------------------------------------------------------------

    public function f_menu_catalog($id=3)
    {
        $this->sql->GetMenuCatalog('top', 3, 1);
        return $this->ApplyTemplate(($this->tpl!=""?$this->tpl:'template.php'), array('menu'=>$this->sql->cur_menu), $this->getClassName());
    }

    public function f_menu_part($id, $type)
    {
        $data = $this->sql->getMenuItems($type);
        return $data;
    }

    public function CombineIdSub($arr, $arr_rosy, $level){
        //printarray($arr);
	if (!empty($arr))
	foreach ($arr as $m){
                        $idd = $this->sql->GetRightNum($m['module_menu_topic_id']);
			//$arr_rosy[]=$m['module_menu_id'];
                        if ($idd) $arr_rosy[] = $idd;
   $arr_rosy = $this->CombineIdSub($m['sub'], $arr_rosy, $level);
	}
        //printarray($arr_rosy);
        return $arr_rosy;
    }

    public function f_menu_sub_topic($id, $id_n=0){
        $arr_rosy = array();
        $rosy = $this->sql->GetMenuCatalog('top', 3, 1);
        //printarray($rosy);
        //$arr_rosy[] = $id_n;
        $arr_rosy[] = intval($this->sql->GetRightNum($id_n));
        //printarray($arr_rosy);
        $arr_rosy = $this->CombineIdSub($rosy[$id]['sub'], $arr_rosy, 1);
        //printarray($arr_rosy);
        $arr_rosy = implode(', ', $arr_rosy);
        //echo ($arr_rosy);
        return $arr_rosy;
    }

    public function GetRightNumMenu($page){
        $newpage = $this->sql->GetRightNumMenu($page);
        return $newpage;
    }
}