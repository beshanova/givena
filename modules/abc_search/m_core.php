<?php
if(!defined("S_INC") || S_INC!==true)die();

#-- самый первый класс, с которого все запускается. Управляющий класс.

class ABC_search extends ExtController
{

    protected $sql=null;
    public $tm_id=0;
    public $tpl='';
    public $func='';
    public $action='';
    protected $app=null;
    const C_NAME = 'ABC_search';
    const C_TITLE = 'Поиск';
    const C_DESC = 'Поиск';
    const C_ISPUB = 0;

    public function __construct()
    {
        global $APP;
        $this->app = $APP;
        $this->sql = new ABC_searchSql();
        $this->action = (isset($_REQUEST['admin_action'])) ? $_REQUEST['admin_action'] : '';
        $this->page = (isset($_REQUEST['p']) && $_REQUEST['p']>0) ? intval($_REQUEST['p']) : 1;
    }

    #-- Функция возвращает контент
    private function getContent()
    {
        if ($this->action=='abc_search_res')
        {
            $data = $this->sql->getSearchData($this->tm_id);

            $tpl = $data['module_search_tpl_results'];

            $data['query'] = trim($_REQUEST['letter']);

            $q_r[] = trim($_REQUEST['letter']);

            if (sizeof($q_r)>0)
            {
                $data['results'] = $this->sql->searchTextInModules($q_r, $data['module_search_cnt_literals']);

                $this->app->topic[0]['module_menu_url'] = preg_replace('~\&p=\d+~', '', $_SERVER['REQUEST_URI']);

                $this->extc_getPagerList(sizeof($data['results']), $data['module_search_cnt_results']);
                $data['pager_list'] = $this->ext_page_text;

                $k = 0;
                foreach ($data['results'] as $t=>$d)
                {
                    $k++;
                    if ($k <= ($this->page-1)*$data['module_search_cnt_results'] || $k > $this->page*$data['module_search_cnt_results'])
                        unset($data['results'][$t]);
                }
            }
            else
                $data['results'] = array();
        }
        else
        {
            $this->sql->getModuleData($this->tm_id, $this->tpl, $this->func);
            $data = $this->sql->data;
            $tpl = $this->sql->data['module_search_tpl_form'];
        }

        return $this->ApplyTemplate($tpl, array('data'=>$data, 'is_active'=>1), $this->getClassName());
    }

    public function __toString()
    {
        $this->actionPopup();
        return $this->getContent();
    }

    public function loadPopupAdminBlockEdit()
    {
        $this->actionPopup();

        $data = $this->sql->getSearchData($this->tm_id);

        print $this->ApplyTemplateAdmin('edit.php', array('search'=>$data, 'cl'=>$this->getClassName(), 'tm_id'=>$this->tm_id), $this->getClassName());
    }

    private function actionPopup()
    {
        switch ($this->action)
        {
            case 'save':
                $this->sql->saveSearchData($this->tm_id);
                $this->app->main_с_saveAdminAction('update', self::C_TITLE.' (ID:'.$this->tm_id.')', $this->action, 'Изменение контента блока');
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