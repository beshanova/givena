<?php
if(!defined("S_INC") || S_INC!==true)die();

#-- самый первый класс, с которого все запускается. Управляющий класс.

class Basket extends ExtController
{

    protected $sql=null;
    public $tm_id='';
    public $tpl='';
    public $func='';
    public $action='';
    protected $app=null;
    const C_NAME = 'Basket';
    const C_TITLE = 'Корзина';
    const C_DESC = 'Корзина';
    const C_ISPUB = 0;

    public function __construct()
    {
        global $APP;
        $this->app = $APP;
        $this->sql = new BasketSql();
        $this->action = (isset($_REQUEST['admin_action'])) ? $_REQUEST['admin_action'] : '';
    }

    #-- Функция возвращает контент
    private function getContent()
    {
        $this->sql->getModuleData($this->tm_id, $this->tpl, $this->func);
        $data = $this->sql->data;
        $data['order'] = $this->sql->getBasketItemsData( $this->tm_id, $this->sql->getCurSessId() );

        $tpl = $data['module_basket_tpl_small'];

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

        $data = $this->sql->getBasketData($this->tm_id);
        $data['order_new'] = $this->sql->getBasketItemsOrders($this->tm_id, 1);
        $data['order_old'] = $this->sql->getBasketItemsOrders($this->tm_id, 2);

        print $this->ApplyTemplateAdmin('edit.php', array('data'=>$data, 'cl'=>$this->getClassName(), 'tm_id'=>$this->tm_id), $this->getClassName());
    }

    public function updateBasket()
    {
        $this->actionPopup();
    }

    private function actionPopup()
    {
        switch ($this->action)
        {
            case 'basket_go':
                $text = $this->sql->updateBasketOrder($this->tm_id, $_REQUEST);

                $data = $this->sql->getBasketData($this->tm_id);
                #-- отправляем сообщение админу о новом заказе
                if ($data['module_basket_emails'])
                {
                    $emails = explode(',', $data['module_basket_emails']);
                    foreach ($emails as $e)
                    {
                        $e = mb_trim($e, 'utf-8');
                        if (preg_match('~^.+@.+\.[а-яa-z0-9]{2,6}$~iu', $e))
                            $this->extc_sendmail($e, 'Новый заказ на сайте', $text, $e);
                    }
                }
                headerTo($_SESSION['_SITE_']['back_url']);
                break;

            case 'del2basket':
                if (isset($_REQUEST['ajax']) && $_REQUEST['ajax']==1)
                {
                    $item_id = intval($_REQUEST['id_item']);

                    $this->sql->deleteItemFromBasket($this->tm_id, $item_id);
                    $data = $this->sql->getBasketData($this->tm_id);
                    $data['order'] = $this->sql->getBasketItemsData( $this->tm_id, $this->sql->getCurSessId() );

                    print $this->ApplyTemplate($data['module_basket_tpl_list'], array('data'=>$data, 'is_active'=>1), $this->getClassName());
                    exit;
                }
                break;

            case 'basket_show':
                if (isset($_REQUEST['ajax']) && $_REQUEST['ajax']==1)
                {
                    $data = $this->sql->getBasketData($this->tm_id);
                    $data['order'] = $this->sql->getBasketItemsData( $this->tm_id, $this->sql->getCurSessId() );
                    $data['client'] = $this->sql->getDopFieldsTable($data['order']);

                    print $this->ApplyTemplate($data['module_basket_tpl_list'], array('data'=>$data, 'is_active'=>1));
                    exit;
                }
                else
                    headerTo($_SESSION['_SITE_']['back_url']);

            case 'add2basket':
                if (isset($_REQUEST['ajax']) && $_REQUEST['ajax']==1)
                {
                    $item_id = intval($_REQUEST['id_item']);
                    $cnt = intval($_REQUEST['cnt']);

                    $data = $this->sql->getBasketData($this->tm_id);
                    $data['order'] = $this->sql->add2Basket($this->tm_id, $item_id, $cnt);

                    print $this->ApplyTemplate($data['module_basket_tpl_small'], array('data'=>$data, 'is_active'=>1), $this->getClassName());
                    exit;
                }
                else
                    headerTo($_SESSION['_SITE_']['back_url']);

            case 'save':
                $this->sql->saveBasketData($this->tm_id);
                $this->app->main_с_saveAdminAction('update', self::C_TITLE.' (ID:'.$this->tm_id.')', $this->action, 'Изменение настроек корзины');
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