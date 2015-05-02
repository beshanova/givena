<?php
if(!defined("S_INC") || S_INC!==true)die();

#-- Класс работы с Каталогом

class _List extends ExtController
{

    public $sql=null;
    public $tm_id=0;
    public $item_id=0;
    public $action='';
    public $func='';
    protected $app=null;
    const C_NAME = '_List';
    const C_TITLE = 'Движек каталожных модулей';
    const C_DESC = 'Движек каталожных модулей';
    #-- в этом модуле данная переменная не должна быть константой, т.к. в зависимости от размещения на странице может меняться
    public $C_ISPUB = 0;
    private $cl = '';

    #-- $cl указатель на дочерний класс, вызвавший данный класс
    public function __construct($cl='')
    {
        global $APP;
        $this->app = $APP;
        $this->sql = new _ListSql(strtolower($cl));
        $this->action = (isset($_REQUEST['admin_action'])) ? $_REQUEST['admin_action'] : '';
        $this->page = (isset($_REQUEST['p']) && $_REQUEST['p']>0) ? intval($_REQUEST['p']) : 1;
        $this->cl = $cl;
    }

    #-- Функция возвращает контент
    public function getContent()
    {
        if (intval($_REQUEST['id'])>0)
        {
            $data = $this->sql->getModuleData($this->tm_id);
            $n = $this->sql->getCatalogItem(intval($_REQUEST['id']));
            if (!$n)
            {
              header_error_404();
              $this->app->is_404 = true;
            }
            $_cont_ = $this->AdminBlockItem( $this->ApplyTemplate($data['module_list_detail_tpl'], array('catalog'=>$n, '_item_'=>1, 'is_active'=>$n['module_list_item_is_active']), $this->getClassName()), array('item_id'=>$n['module_list_item_id'], 'is_active'=>$n['module_list_item_is_active']) );

            if (preg_match('~catalog~i',$this->cl))
            {
                $bask = new Basket();
                $this->sql->saveSeenItem(intval($_REQUEST['id']), $bask->getCurSessId());
            }
        }
        else
        {
            $data = $this->sql->getModuleData($this->tm_id);
            $catalog_cnt = $this->sql->getModuleCountItems($this->tm_id);
           
            if (in_array($this->tm_id, array(8)))
                $catalog_li = $this->sql->getModuleItemsNews($this->tm_id, $data['module_list_count'], $this->page);
            else
                $catalog_li = $this->sql->getModuleItems($this->tm_id, $data['module_list_count'], $this->page);

            $this->extc_getPagerList($catalog_cnt, $data['module_list_count']);
            $_cont_ .= $this->ext_page_text;

            $j = 0;
            foreach ($catalog_li as $n)
            {
                $prices = array();
                $prices[] = ($n['f_p_act'] && $n['f_p_see']) ? $n['f_price'] : '';
                $prices[] = ($n['f_p2_act'] && $n['f_p2_see']) ? $n['f_price2'] : '';
                $prices[] = ($n['f_p3_act'] && $n['f_p3_see']) ? $n['f_price3'] : '';
                $prices[] = ($n['f_p4_act'] && $n['f_p4_see']) ? $n['f_price4'] : '';
                $price_mass = array_filter($prices, "filter_price_mass");
                //array($n['f_price'],$n['f_price2'],$n['f_price3'],$n['f_price4'])
                //$price_mass = array_filter($prices);
                $_cont_ .= $this->AdminBlockItem( $this->ApplyTemplate($data['module_list_list_tpl'], array('price_mass'=>$price_mass, 'catalog'=>$n, '_item_'=>++$j, 'is_active'=>$n['module_list_item_is_active']), $this->getClassName()), array('item_id'=>$n['module_list_item_id'], 'is_active'=>$n['module_list_item_is_active']) );
            }

            $_cont_ .= $this->ext_page_text;
        }
        return $this->AdminBlock($_cont_, array('is_active'=>$data['topics_module_is_active'])) ;
    }

    public function loadPopupAdminBlockEdit()
    {
        if (!$this->sql)
            self::__construct($this->getClassName());

        $this->actionPopup();

        #-- получаем все разделы данного типа, чтобы сделать возможным привязку к другим разделам
        $groups = $this->sql->getGroupsList($this->cl, $this->item_id);

        #-- если условие верно, то значит редактируется элемент молуля
        if ($this->item_id>0)
        {
            $catalog = $this->sql->getCatalogItem($this->item_id);
            $fields = $this->sql->getDopFieldsTable($catalog);

            print $this->ApplyTemplateAdmin('edit_item.php', array('catalog'=>$catalog, 'fields'=>$fields, 'groups'=>$groups, 'cl'=>$this->getClassName(), 'tm_id'=>$this->tm_id), self::C_NAME);
        }
        else
        {
            $fields = $this->sql->getDopFieldsTable();
            $data = $this->sql->getModuleData($this->tm_id);
            $tpls_l = $this->extc_getTemplatesList($this->getClassName() . '/list');
            $tpls_d = $this->extc_getTemplatesList($this->getClassName() . '/detail');

            print $this->ApplyTemplateAdmin('edit.php', array('catalog'=>$data, 'fields'=>$fields, 'groups'=>$groups, 'tpls_l'=>$tpls_l, 'tpls_d'=>$tpls_d, 'cl'=>$this->getClassName(), 'tm_id'=>$this->tm_id), self::C_NAME);
        }
    }

    private function actionPopup()
    {
        switch ($this->action)
        {
            case 'down':
            case 'up':
                $this->sql->saveItemSort($this->action, $this->item_id, $this->tm_id);
                $this->app->main_с_saveAdminAction('sort', self::C_TITLE.' (ID:'.$this->tm_id.')', $this->action, 'Сортировка элементов');
                headerTo($_SESSION['_SITE_']['back_url']);
            case 'item_delete':
                $this->sql->deleteCatalogItem($this->item_id, $this->tm_id);
                $this->app->main_с_saveAdminAction('delete', self::C_TITLE.' (ID:'.$this->tm_id.')', $this->action, 'Удаление элемента');
                headerTo($_SESSION['_SITE_']['back_url']);
            case 'save_item':
                $this->sql->saveItemCatalog($this->item_id);
                $this->app->main_с_saveAdminAction('update', self::C_TITLE.' (ID:'.$this->tm_id.')', $this->action, 'Изменение элемента');
                headerTo($_SESSION['_SITE_']['back_url']);
            case 'delete':
                $this->sql->deleteContentBlock($this->tm_id);
                $this->app->main_с_saveAdminAction('delete', self::C_TITLE.' (ID:'.$this->tm_id.')', $this->action, 'Удаление блока');
                headerTo($_SESSION['_SITE_']['back_url']);
            case 'save':
                $this->sql->saveCatalogData($this->tm_id);
                $this->app->main_с_saveAdminAction('update', self::C_TITLE.' (ID:'.$this->tm_id.')', $this->action, 'Изменение настроек блока/добавление элемента');
                headerTo($_SESSION['_SITE_']['back_url']);
            default:
        }
    }

    function saveNewBlock()
    {
        if (!$this->sql)
            self::__construct($this->getClassName());
        $this->sql->saveNewBlock($this->tm_id);
        $this->app->main_с_saveAdminAction('insert', self::C_TITLE.' (ID:'.$this->tm_id.')', $this->action, 'Добавление блока');
    }

    private function __clone() {}

    public function __destruct() {}

    public function getClassName() { return $this->cl; }
    public function getClassTitle() { return self::C_TITLE; }
    public function getClassDesc() { return self::C_DESC; }
    public function getClassIsPub() { return $this->C_ISPUB; }


    #-- данную функцию использует модуль поиска
    public function searchContentModule($tm_ids, $q_arr)
    {
        if (!$this->sql)
            self::__construct($this->getClassName());
        return $this->sql->searchContentModule($tm_ids, $q_arr);
    }

    #-- данную функцию использует модуль корзины
    public function add2BasketItem($item_id, $pr)
    {
        if (!$this->sql)
            self::__construct($this->getClassName());
        return $this->sql->add2BasketItem($item_id, $pr);
    }

}