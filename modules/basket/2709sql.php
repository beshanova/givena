<?php
if(!defined("S_INC") || S_INC!==true)die();
#-- Класс модуля для работы с БД. Должен наследовать класс DB, для подключения функций работы с запросами к БД.

class BasketSql extends DB
{

    private $cur_url = array();

    function __construct()
    {
        parent::__construct();
    }

    function getBasketData($type)
    {
        $sql = 'SELECT * FROM ?_module_basket WHERE module_basket_type=?s';
        return $this->query($sql, array($type));
    }

    function getBasketItemsData($type, $sess_id, $is_adm=0)
    {
        $sql = 'SELECT * FROM ?_module_basket_items WHERE module_basket_item_type=?s AND module_basket_item_session=?s AND module_basket_item_status=0';
        $order = $this->query($sql, array($type, $sess_id));
        $order['cnt'] = 0;
        $order['summ'] = 0;
        if ($order['module_basket_item_order']!='')
        {
            $datas = unserialize($order['module_basket_item_order']);
            foreach ($datas as $item_id=>$d)
            {
                $order['cnt'] += $d['cnt'];
                $order['summ'] += ($d['f_price']*$d['cnt']);
                $order['items'][$item_id] = $d;
                $order['items'][$item_id]['url'] = $d['url'] . (preg_match('~id=\d~', $d['url']) ? '' : ( (preg_match('~\?~', $d['url'])?'&':'?') . 'id='.$item_id ));
            }
        }
        return $order;
    }

    function getBasketItemById($id)
    {
        $sql = 'SELECT * FROM ?_module_basket_items WHERE module_basket_item_id=?i';
        return $this->query($sql, array($id));
    }

    function getBasketItemsOrders($type, $status=1)
    {
        $orders = array();
        $sql = 'SELECT * FROM ?_module_basket_items WHERE module_basket_item_type=?s AND module_basket_item_status=?i ORDER BY module_basket_item_date_update DESC';
        $res = $this->mysqlquery($sql, array($type, $status));
        while ($r = $this->fetchOne($res))
        {
            if ($r['module_basket_item_order']!='')
            {
                $order = $r;
                unset($order['module_basket_item_order']);
                $datas = unserialize($r['module_basket_item_order']);
                foreach ($datas['items'] as $item_id=>$d)
                {
                    $order['cnt'] += $d['cnt'];
                    $order['summ'] += ($d['f_price']*$d['cnt']);
                    $d['price'] = $d['f_price'];
                    unset($d['f_price']);
                    $order['items'][$item_id] = $d;
                }
                $order['client'] = $this->getDopFieldsTable($r);
                $orders[] = $order;
            }
        }
        return $orders;
    }

    function getModuleData($type, $tpl_f, $tpl_r)
    {
        $this->data = $this->getBasketData($type);

        if (empty($this->data))
        {
            $sql = 'INSERT INTO ?_module_basket SET module_basket_type=?s, module_basket_tpl_small=?s, module_basket_tpl_list=?s, module_basket_date_update=NOW()';
            $this->mysqlquery($sql, array($type, $tpl_f, $tpl_r));

            $this->data = $this->getBasketData($type);
        }
        elseif ( ($tpl_f != "" && $this->data['module_basket_tpl_small'] != $tpl_f) || ($tpl_r != "" && $this->data['module_basket_tpl_list'] != $tpl_r))
        {
            $sql = 'UPDATE ?_module_basket SET module_basket_tpl_small=?s, module_basket_tpl_list=?s WHERE module_basket_type=?s';
            $this->mysqlquery($sql, array($tpl_f, $tpl_r, $type));

            $this->data['module_basket_tpl_small'] = $tpl_f;
            $this->data['module_basket_tpl_list'] = $tpl_r;
        }
    }

    function updateBasketOrder($type, $data)
    {
        $ins_txt = '';

        $sess_id = $this->getCurSessId();
        $order = $this->getBasketItemsData($type, $sess_id);
        $n_order = intval($order['module_basket_item_id']);
        $order['cnt'] = 0;
        $order['summ'] = 0;

        $o_text = '<table border="1" cellpadding="5">';
        $o_text.= '<tr><td>Товар</td><td>Цена</td><td>Кол-во</td><td>Сумма, руб.</td></tr>'.N;
        foreach ($data['cnt'] as $pid=>$cnt)
        {
            $order['items'][$pid]['cnt'] = $cnt;
            $order['cnt'] += $cnt;
            $order['summ'] += $cnt*$order['items'][$pid]['f_price'];
            eval('$pr = PR'.$order['items'][$pid]['pr'].';');

            $o_text.= '<tr><td>' . $order['items'][$pid]['f_title'] . ($order['items'][$pid]['category']?' / '.$order['items'][$pid]['category']:'') . BR . $pr . '</td><td>' . $order['items'][$pid]['f_price'] . '</td><td>' . $cnt . '</td><td>' . ($order['items'][$pid]['f_price']*$cnt) . '</td></tr>'.N;
        }
        $o_text.= '<tr><td colspan="3">Итого</td><td>' . $order['summ'] . '</td></tr>'.N;
        $o_text.= '</table>';
        $cnt = $order['cnt'];
        $summ = $order['summ'];
        $order = serialize($order);

        $ins_arr = array($order);

        $fields = $this->getDopFieldsTable($data);
        if (sizeof($fields)>0)
            foreach ($fields as $v)
            {
                $ins_txt .= ', `'.$v['COLUMN_NAME'].'`=?'.$v['dev'];
                $ins_arr[] = $v['value'];
                $c_text .= $v['title'].": ".$v['value'].N;
            }

        $ins_arr[] = $type;
        $ins_arr[] = $sess_id;
        $sql = 'UPDATE ?_module_basket_items SET module_basket_item_order=?s, module_basket_item_status=1, module_basket_item_date_update=NOW() '.$ins_txt.' WHERE module_basket_item_type=?s AND module_basket_item_session=?s AND module_basket_item_status=0';
        $this->mysqlquery($sql, $ins_arr);

        $text= "Бланк заказа:" . BR . $o_text;

        return array('text'=>$text, 'id'=>$n_order, 'cnt'=>$cnt, 'summ'=>$summ);
    }

    function saveBasketData($type)
    {
        $sql = 'UPDATE ?_module_basket SET module_basket_emails=?s, module_basket_date_update=NOW() WHERE module_basket_type=?s';
        $this->mysqlquery($sql, array($_REQUEST['emails'], $type));
    }

    function getCurSessId()
    {
        if (!isset($_COOKIE['sess_id']))
        {
            $sess_id = md5(uniqid(rand(),true));
            $_COOKIE['sess_id'] = $sess_id;
            setcookie('sess_id', $sess_id, time()+3600*364, '/');
        }
        else
            $sess_id = substr($_COOKIE['sess_id'], 0, 31);
        return $sess_id;
    }

    function add2Basket($type, $item_id, $cnt=1, $pr=1)
    {
        $sess_id = $this->getCurSessId();

        if (in_array($pr, array(1,2,3,4)))
        {
            $sql = 'SELECT * FROM ?_module_basket_items WHERE module_basket_item_type=?s AND module_basket_item_session=?s AND module_basket_item_status=0';
            $q = $this->query($sql, array($type, $sess_id));
            if (!empty($q))
            {
                $datas = unserialize($q['module_basket_item_order']);

                if (!isset($datas[$item_id.'_'.$pr]))
                {
                    $cat = new _List(ucfirst($type));
                    $datas[$item_id.'_'.$pr] = $cat->add2BasketItem($item_id, $pr);
                }
                $datas[$item_id.'_'.$pr]['cnt'] += $cnt;
                $datas[$item_id.'_'.$pr]['url'] = urldecode($_REQUEST['bu']);
                #-- сохраняем тип цены по которому был приобретен товар
                $datas[$item_id.'_'.$pr]['pr'] = $pr;
                $datas = serialize($datas);

                $sql = 'UPDATE ?_module_basket_items SET module_basket_item_order=?s, module_basket_item_date_update=NOW() WHERE module_basket_item_type=?s AND module_basket_item_session=?s AND module_basket_item_status=0';
                $this->mysqlquery($sql, array($datas, $type, $sess_id));
            }
            else
            {
                $cat = new _List(ucfirst($type));
                $datas[$item_id.'_'.$pr] = $cat->add2BasketItem($item_id, $pr);
                $datas[$item_id.'_'.$pr]['cnt'] += $cnt;
                $datas[$item_id.'_'.$pr]['url'] = urldecode($_REQUEST['bu']);
                $datas[$item_id.'_'.$pr]['pr'] = $pr;
                $datas = serialize($datas);

                $sql = 'INSERT INTO ?_module_basket_items SET module_basket_item_order=?s, module_basket_item_type=?s, module_basket_item_session=?s, module_basket_item_date_update=NOW()';
                $this->mysqlquery($sql, array($datas, $type, $sess_id));
            }
        }

        $order = $this->getBasketItemsData($type, $sess_id);
        return $order;
    }

    #-- функции для работы с дополнительными полями
    public function getDopFieldsTable($data = array())
    {
        $fields = array();
        $sql = 'SELECT COLUMN_NAME, COLUMN_TYPE, COLUMN_COMMENT FROM information_schema.columns WHERE TABLE_NAME="?_module_basket_items" AND TABLE_SCHEMA=?s AND COLUMN_NAME LIKE "b_%"';
        $res = $this->mysqlquery($sql, array( $this->db_getDbName() ));
        while ($r = $this->fetchOne($res))
        {
            $r = array_merge( $r, $this->getFieldType( $r, trim($data[$r['COLUMN_NAME']]) ) );
            $fields[$r['COLUMN_NAME']] = $r;
        }
        return $fields;
    }

    private function getFieldType($r, $value="")
    {
        $data = array();

        #-- вид поля в админке
        if (preg_match('~^([^\|.]+)\|([^\|.]+)$~isU', $r['COLUMN_COMMENT'], $ar))
        {
            $title = trim($ar[1]);
            $type = trim($ar[2]);
        }
        else
            return array();

        $name = $r['COLUMN_NAME'];

        $data['title'] = $title;
        $data['f_type'] = $type;
        $data['is_need'] = (preg_match('~\*~', $title)) ? 1 : 0;
        $class_e = ($data['is_need']>0) ? ' basket-field-is-need-00 basket-field-00' : ' basket-field-00';
        switch ($type)
        {
            case 'text':
                $data['field'] = '<input type="text" id="b-' . $name . '" class="admin-field-style-text' . $class_e . '" name="' . $name . '" value="' . htmlspecialchars($value) . '" />';
                $data['dev'] = 's';
                $data['value'] = $value;
                break;

            case 'textarea':
                $data['field'] = '<textarea id="b-' . $name . '" class="admin-field-style-textarea' . $class_e . '" name="' . $name . '">' . $value . '</textarea>';
                $data['dev'] = 's';
                $data['value'] = $value;
                break;

            case 'radio':
                if (preg_match_all("~'(.+)'~isU", $r['COLUMN_TYPE'], $ar))
                {
                    $data['field'] = '';
                    foreach ($ar[1] as $a)
                    {
                        $data['field'][] = '<input type="radio" id="b-' . $name . '" class="admin-field-style-radio" name="' . $name . '" value="' . htmlspecialchars($a) . '"' . ($value==$a ? ' checked' : '') . ' /> - ' . $a;
                    }
                    $data['dev'] = 's';
                    $data['value'] = $value;
                }
                break;

            case 'checkbox':
                $data['field'] = '<input type="checkbox" id="b-' . $name . '" class="admin-field-style-checkbox" name="' . $name . '" value="1"' . ($value ? ' checked' : '') . '/> - Да/Нет';
                $data['dev'] = 'i';
                $data['value'] = ($value) ? $value : '';
                break;

            case 'select':
                if (preg_match_all("~'(.+)'~isU", $r['COLUMN_TYPE'], $ar))
                {
                    $data['field'][] = '<select id="b-' . $name . '" class="admin-field-style-select' . $class_e . '" name="' . $name . '">';
                    foreach ($ar[1] as $a)
                    {
                        $data['field'][] = '<option value="' . htmlspecialchars($a) . '"' . ($a==$value?' selected':'') . '>' . $a . '</option>';
                    }
                    $data['field'][] = '</select>';
                    $data['dev'] = 's';
                    $data['value'] = $value;
                }
                break;

            default:
                $data['field'] = '<i>Тип поля задан неверно!</i>';
        }
        return $data;
    }

    function deleteItemFromBasket($type, $item_id)
    {
        $sess_id = $this->getCurSessId();

        $order = $this->getBasketItemsData($type, $sess_id);
        $datas = unserialize($order['module_basket_item_order']);
        unset($datas[$item_id]);

        $datas = serialize($datas);
        $sql = 'UPDATE ?_module_basket_items SET module_basket_item_order=?s, module_basket_item_date_update=NOW() WHERE module_basket_item_type=?s AND module_basket_item_session=?s AND module_basket_item_status=0';
        $this->mysqlquery($sql, array($datas, $type, $sess_id));
    }

}
?>