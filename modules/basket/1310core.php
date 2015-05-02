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
    public $item_id=0;
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
        $this->page = (isset($_REQUEST['p']) && $_REQUEST['p']>0) ? intval($_REQUEST['p']) : 1;
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

        $cnt_on_p = 10;
        $cur_st = (isset($_REQUEST['cur_status'])) ? intval($_REQUEST['cur_status']) : 1;

        $data = $this->sql->getBasketData($this->tm_id);
        //$data['order_new'] = $this->sql->getBasketItemsOrders($this->tm_id, 1);
        //$data['order_old'] = $this->sql->getBasketItemsOrders($this->tm_id, 2);

        $status = $this->sql->getStatusesList();

        foreach ($status as $s_id=>$s)
        {
            $page = ($cur_st==$s_id) ? $this->page : 1;
            $data_c[$s_id] = $this->sql->getBasketCntItemsOrders($this->tm_id, $s_id);
            $data[$s_id] = $this->sql->getBasketItemsOrders($this->tm_id, $s_id, $page, $cnt_on_p);
            $this->extc_getPagerListAdminAjax($data_c[$s_id], $cnt_on_p, $page, 'nextPageList(#PAGE#,'.$s_id.');');
            $pages[$s_id] = $this->ext_page_text;
        }

        print $this->ApplyTemplateAdmin('edit.php', array('data'=>$data, 'cl'=>$this->getClassName(), 'tm_id'=>$this->tm_id, 'status'=>$status, 'pages'=>$pages, 'cur_st'=>$cur_st), $this->getClassName());
    }

    public function updateBasket()
    {
        $this->actionPopup();
    }

    private function actionPopup()
    {



        switch ($this->action)
        {
            case 'upd_summ':
                $summ = str_replace(',','.',$_REQUEST['summ']);
                $this->sql->updateDopSumm($this->tm_id, $this->item_id, $summ);
                print 'ok';
                exit;

            case 'basket_go':
                #-- регистрируем пользователя
                $profile_id = intval($_SESSION['_SITE_']['profiledata']['module_profile_item_id']);
                if ( ! $profile_id )
                {
                    $data['e'] = trim($_REQUEST['b_email']);
                    $data['p'] = $data['p2'] = substr(md5(session_id().time()),0,6);
                    $data['p_family'] = trim($_REQUEST['b_fio']);
                    //$data['p_name'] = trim($_REQUEST['b_name']);
                    $data['p_adress'] = trim($_REQUEST['b_adress']);
                    $data['p_phone'] = trim($_REQUEST['b_phone']);

                    $_REQUEST = array_merge($data, $_REQUEST);

                    $profile = new Profile();
                    $profile->tm_id = 'form';
                    $profile->regUserData($data);
                }

                $order = $this->sql->updateBasketOrder($this->tm_id, $_REQUEST);
				        $this->app->main_c_saveEmailIntoSendmail($_REQUEST['b_email'], $_REQUEST['b_fio']);
                $text0 = $order['text'];

                $data = $this->sql->getBasketData($this->tm_id);

                #-- отправляем сообщение админу о новом заказе
                if ($data['module_basket_emails'] && $order['id']>0)
                {
                    $order0 = $this->sql->getBasketItemById($order['id']);
                    $dt = date('d.m.Y', strtotime($order0['module_basket_item_date_update']));

                    $emails = explode(',', $data['module_basket_emails']);
                    foreach ($emails as $e)
                    {
                        $e = mb_trim($e, 'utf-8');
                        if (preg_match('~^.+@.+\.[а-яa-z0-9]{2,6}$~iu', $e))
                        {
                            $text = 'Заказ растений №' . $order['id'] . ' от ' . $dt . '.' . BR;
                            $text.= $text0 . BR . BR;
                            $text.= "Данные клиента:" . BR;
                            $text.= "Способ доставки: " . $order0['b_deliv'] . BR;
                            $text.= "Способ оплаты: " . $order0['b_buy'] . BR;
                            $text.= "ФИО: " . $order0['b_fio'] . BR;
                            $text.= "E-mail: " . $order0['b_email'] . BR;
                            $text.= "Телефон: " . $order0['b_phone'] . BR;
                            $text.= "Индекс: " . $order0['b_index'] . BR;
                            $text.= "Адрес: " . $order0['b_adress'] . BR;
                            $text.= "Комментарий: " . BR . nl2br($order0['b_comment']);

                            $this->extc_sendmail($e, 'Заявка с сайта Givena.ru', $text, 'Givena.ru <'.$e.'>', 1);
                        }
                    }

                    #-- отправляем письмо клиенту о его заказе
                    if (preg_match('~^.+@.+\.[а-яa-z0-9]{2,6}$~iu', $_REQUEST['b_email']))
                    {
                        $sess_id = $this->sql->getCurSessId();

                        $text = "Ваш заказ №" . $order['id'] . " от " . $dt . " успешно принят." . BR;
                        $text.= $text0 . BR . BR;
                        $text.= "Способ доставки: " . $order0['b_deliv'] . BR;
                        $text.= "Способ оплаты: " . $order0['b_buy'] . "." . BR;
						$text.= "Резервирование товара, происходит после предоплаты счета, размещенного в личном кабинете в истории заказов". BR;
                        $text.= "Спасибо Вам, что выбрали нашу компанию!";

                        $this->extc_sendmail($order0['b_email'], 'Заказ растений №'.$order['id'].' от '.$dt, $text, 'Givena.ru <'.$emails[0].'>', 1);
                    }
                }

                if ($_SESSION['_SITE_']['profiledata']['module_profile_item_id']>0)
                    headerTo('/profile/?cl=Profile&tm=form&a=index');
                else
                    headerTo($_SESSION['_SITE_']['back_url']);
                break;

            case 'del2basket':
                if (isset($_REQUEST['ajax']) && $_REQUEST['ajax']==1)
                {
                    $item_id = $_REQUEST['id_item'];

                    $this->sql->deleteItemFromBasket($this->tm_id, $item_id);
                    $data = $this->sql->getBasketData($this->tm_id);
                    $data['order'] = $this->sql->getBasketItemsData( $this->tm_id, $this->sql->getCurSessId() );
                    if (isset($_SESSION['_SITE_']['profiledata']) && $_SESSION['_SITE_']['profiledata']['module_profile_item_id']>0)
                    {
                        if ($data['order']['b_fio']=='')
                            $data['order']['b_fio'] = $_SESSION['_SITE_']['profiledata']['p_family'];
                        /*if ($data['order']['b_name']=='')
                            $data['order']['b_name'] = $_SESSION['_SITE_']['profiledata']['p_name'];*/
                        if ($data['order']['b_email']=='')
                            $data['order']['b_email'] = $_SESSION['_SITE_']['profiledata']['module_profile_item_email'];
                        if ($data['order']['b_phone']=='')
                            $data['order']['b_phone'] = $_SESSION['_SITE_']['profiledata']['p_phone'];
                        if ($data['order']['b_adress']=='')
                            $data['order']['b_adress'] = $_SESSION['_SITE_']['profiledata']['p_adress'];
                    }

                    $data['client'] = $this->sql->getDopFieldsTable($data['order']);

                    print $this->ApplyTemplate($data['module_basket_tpl_list'], array('data'=>$data, 'is_active'=>1), $this->getClassName());
                    exit;
                }
                break;

            case 'basket_show':
                if (isset($_REQUEST['ajax']) && $_REQUEST['ajax']==1)
                {
                    $data = $this->sql->getBasketData($this->tm_id);
                    $data['order'] = $this->sql->getBasketItemsData( $this->tm_id, $this->sql->getCurSessId() );
                    if (isset($_SESSION['_SITE_']['profiledata']) && $_SESSION['_SITE_']['profiledata']['module_profile_item_id']>0)
                    {
                        if ($data['order']['b_fio']=='')
                            $data['order']['b_fio'] = $_SESSION['_SITE_']['profiledata']['p_family'];
                        /*if ($data['order']['b_name']=='')
                            $data['order']['b_name'] = $_SESSION['_SITE_']['profiledata']['p_name'];*/
                        if ($data['order']['b_email']=='')
                            $data['order']['b_email'] = $_SESSION['_SITE_']['profiledata']['module_profile_item_email'];
                        if ($data['order']['b_phone']=='')
                            $data['order']['b_phone'] = $_SESSION['_SITE_']['profiledata']['p_phone'];
                        if ($data['order']['b_adress']=='')
                            $data['order']['b_adress'] = $_SESSION['_SITE_']['profiledata']['p_adress'];
                    }

                    $data['client'] = $this->sql->getDopFieldsTable($data['order']);
                    print $this->ApplyTemplate($data['module_basket_tpl_list'], array('data'=>$data, 'is_active'=>1));
                    exit;
                }
                else
                    headerTo($_SESSION['_SITE_']['back_url']);

            case 'add2basket':
                if (isset($_REQUEST['ajax']) && $_REQUEST['ajax']==1)
                {
                    #-- указатель на тип цены, по которой происходит покупка товара
                    $pr = intval($_REQUEST['pr']);

                    $item_id = intval($_REQUEST['id_item']);
                    $cnt = intval($_REQUEST['cnt']);


                    $data = $this->sql->getBasketData($this->tm_id);

                    $data['order'] = $this->sql->add2Basket($this->tm_id, $item_id, $cnt, $pr);

                    print $this->ApplyTemplate($data['module_basket_tpl_small'], array('data'=>$data, 'is_active'=>1), $this->getClassName());
                    exit;
                }
                else
                    headerTo($_SESSION['_SITE_']['back_url']);

            #-- перевод заказа в другой статус
            case 'new_status':
                $this->sql->updateOrderStatus($this->item_id, intval($_REQUEST['status']));
                $this->app->main_с_saveAdminAction('update', self::C_TITLE.' (ID:'.$this->tm_id.')', $this->action, 'Изменение статуса заказа №'.$this->item_id);
                if ($_REQUEST['act']=='profile')
                {
                    print 'ok';
                    exit;
                }
                break;

            case 'save':
                $this->sql->saveBasketData($this->tm_id);
                $this->app->main_с_saveAdminAction('update', self::C_TITLE.' (ID:'.$this->tm_id.')', $this->action, 'Изменение настроек корзины');
                headerTo($_SESSION['_SITE_']['back_url']);
            default:
        }
    }

    public function getCurSessId()
    {
        return $this->sql->getCurSessId();
    }

    private function __clone() {}

    public function __destruct() {}

    public function getClassName() { return self::C_NAME; }
    public function getClassTitle() { return self::C_TITLE; }
    public function getClassDesc() { return self::C_DESC; }
    public function getClassIsPub() { return self::C_ISPUB; }


    public function getOrderDataByID($id, $profile_id=0)
    {
        $order = array();
        if ($this->sql->testOrderByProfile($id, $profile_id))
        {
            $order = $this->sql->getBasketItemById($id);
            $datas = unserialize($order['module_basket_item_order']);
            foreach ($datas['items'] as $item_id=>$d)
            {
                $order['cnt'] += $d['cnt'];
                $order['summ'] += ($d['f_price']*$d['cnt']);
                $d['price'] = $d['f_price'];
                unset($d['f_price']);
                $order['items'][$item_id] = $d;
            }
        }
        return $order;
    }

}