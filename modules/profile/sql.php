<?php
if(!defined("S_INC") || S_INC!==true)die();
#-- Класс модуля для работы с БД. Должен наследовать класс DB, для подключения функций работы с запросами к БД.

class ProfileSql extends DB
{
    public $p_data = array();

    function __construct()
    {
        parent::__construct();
    }

    public function getProfileData($type, $tpl_auth='', $tpl_reg='')
    {
        $sql = 'SELECT * FROM ?_module_profile WHERE module_profile_type=?s';
        $this->p_data = $this->query($sql, array($type));
        if (empty($this->p_data) && $tpl_auth!='')
        {
            $sql = 'INSERT INTO ?_module_profile SET module_profile_type=?s, module_profile_tpl_auth=?s, module_profile_tpl_reg=?s, module_profile_date_update=NOW()';
            $this->mysqlquery($sql, array($type, $tpl_auth, $tpl_reg));
            $sql = 'SELECT * FROM ?_module_profile WHERE module_profile_type=?s';
            $this->p_data = $this->query($sql, array($type));
        }
        elseif (($tpl_auth!='' && $tpl_reg!='' && $this->p_data['module_profile_tpl_auth']!=$tpl_auth) || ($tpl_auth!='' && $tpl_reg!='' && $this->p_data['module_profile_tpl_reg']!=$tpl_reg))
        {
            $sql = 'UPDATE ?_module_profile SET module_profile_tpl_auth=?s, module_profile_tpl_reg=?s, module_profile_date_update=NOW() WHERE module_profile_type=?s';
            $this->mysqlquery($sql, array($tpl_auth, $tpl_reg, $type));
        }
    }

    public function saveProfileData($type)
    {
        $sql = 'UPDATE ?_module_profile SET module_profile_emails=?s, module_profile_email_from=?s, module_profile_is_confirm=?i, module_profile_date_update=NOW() WHERE module_profile_type=?s';
        $this->mysqlquery($sql, array(trim($_REQUEST['emails']), trim($_REQUEST['email_from']), $_REQUEST['is_confirm'], $type));
    }

    public function updateProfileCodeByEmail($type, $email, $code)
    {
        $sql = 'UPDATE ?_module_profile_items SET module_profile_item_code=?s WHERE module_profile_item_type=?s AND module_profile_item_email=?s';
        $this->mysqlquery($sql, array($code, $type, $email));
    }

    public function saveProfileItems($type, $fields=array())
    {
        foreach ($_REQUEST['is_edit'] as $uid=>$v)
        {
            if ($v==1 && $fields)
            {
                $sql_ar[] = $type;
                foreach ($fields as $k=>$v)
                {
                    $sql_text .= ', `'.$k.'`=?'.$v['dev'];
                    $sql_ar[] = $_REQUEST[$k][$uid];
                }
                $sql = 'UPDATE ?_module_profile_items SET module_profile_item_type=?s '.$sql_text.' WHERE module_profile_item_id=?i';
                $sql_ar[] = $uid;
                $this->mysqlquery($sql, $sql_ar);
            }
        }
    }

    public function updateProfilePwdEmailCode($type, $email, $code, $pwd)
    {
        $sql = 'SELECT * FROM ?_module_profile_items WHERE module_profile_item_type=?s AND module_profile_item_email=?s AND module_profile_item_code=?s';
        $q = $this->query($sql, array($type, $email, $code));

        if ($q['module_profile_item_id']>0)
        {
            $sql = 'UPDATE ?_module_profile_items SET module_profile_item_pwd=?s WHERE module_profile_item_type=?s AND module_profile_item_email=?s AND module_profile_item_code=?s';
            $this->mysqlquery($sql, array($pwd, $type, $email, $code));
            $met = true;
        }
        else
        {
            $sql = 'UPDATE ?_module_profile_items SET module_profile_item_code="" WHERE module_profile_item_type=?s AND module_profile_item_email=?s';
            $this->mysqlquery($sql, array($type, $email));
            $met = false;
        }
        return $met;
    }

    public function updateProfileActiveEmailCode($type, $email, $code)
    {
        $sql = 'SELECT * FROM ?_module_profile_items WHERE module_profile_item_type=?s AND module_profile_item_email=?s AND module_profile_item_code=?s AND module_profile_item_active=0';
        $q = $this->query($sql, array($type, $email, $code));

        if ($q['module_profile_item_id']>0)
        {
            $sql = 'UPDATE ?_module_profile_items SET module_profile_item_code="", module_profile_item_active=1 WHERE module_profile_item_type=?s AND module_profile_item_id=?i';
            $this->mysqlquery($sql, array($type, $q['module_profile_item_id']));
            $met = true;
        }
        else
            $met = false;
        return $met;
    }

    public function saveProfileItemsData($type, $uid, $fields=array())
    {
        $sql_ar[] = $type;
        foreach ($fields as $k=>$v)
        {
            $sql_text .= ', `'.$k.'`=?'.$v['dev'];
            $sql_ar[] = $_REQUEST[$k];
        }
        $sql = 'UPDATE ?_module_profile_items SET module_profile_item_type=?s '.$sql_text.' WHERE module_profile_item_id=?i';
        $sql_ar[] = $uid;
        $this->mysqlquery($sql, $sql_ar);
    }

    public function saveProfileItemPassword($uid, $pass)
    {
        $sql = 'UPDATE ?_module_profile_items SET module_profile_item_pwd=?s WHERE module_profile_item_id=?i';
        $this->mysqlquery($sql, array($pass, $uid));
    }

    public function getUsersList($tm, $page=1, $cnt=10)
    {
        $sql = 'SELECT * FROM ?_module_profile_items WHERE module_profile_item_type=?s ORDER BY module_profile_item_email ASC LIMIT ?i, ?i';
        return $this->fetchAll($sql, array($tm, ($page-1)*$cnt, $cnt));
    }

    public function getUsersCnt($tm)
    {
        $sql = 'SELECT COUNT(*) as CNT FROM ?_module_profile_items WHERE module_profile_item_type=?s';
        $q = $this->query($sql, array($tm));
        return intval($q['CNT']);
    }

    public function getOrdersProfileList($type, $uid)
    {
        $orders = array();
        $sql = 'SELECT * FROM ?_module_basket_items LEFT JOIN ?_module_basket_status ON module_basket_status_id= 	module_basket_item_status WHERE module_basket_item_type=?s AND module_basket_profile_item_id=?i AND module_basket_item_status<>0 ORDER BY module_basket_item_date_update DESC';
        $res = $this->mysqlquery($sql, array($type, $uid));
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
                $orders[] = $order;
            }
        }
        return $orders;
    }

    public function getStatusesListP()
    {
        $datas = array();
        $res = $this->mysqlquery('SELECT * FROM ?_module_basket_status ORDER BY module_basket_status_sortby');
        while ($r = $this->fetchOne($res))
        {
          $datas[$r['module_basket_status_id']] = $r['module_basket_status_title'];
        }
        return $datas;
    }

    public function addNewUserSite($tm_id, $user, $fields)
    {
        $this->getProfileData($tm_id);

        $sql_ar = array();
        $sql_txt = 'module_profile_item_type=?s, module_profile_item_email=?s, module_profile_item_pwd=?s';
        $sql_ar[] = $tm_id;
        $sql_ar[] = $user['e'];
        $sql_ar[] = $user['p'];

        #-- регистрацию подтверждает администратор = 0 вручную через админку
        if ($this->p_data['module_profile_is_confirm']==0)
            $sql_txt .= ', module_profile_item_active=0';
        #-- подтверждает пользователь по ссылке, присланной на указанный e-mail = 1
        elseif ($this->p_data['module_profile_is_confirm']==1)
        {
            $sql_txt .= ', module_profile_item_active=0, module_profile_item_code=?s';
            $sql_ar[] = substr( md5($user['e'].'|'.$user['p'].'D@'.rand(1,100)), 5, 10);
        }
        #-- без какого-либо подтверждения, профиль сразу активен = 2
        elseif ($this->p_data['module_profile_is_confirm']==2)
            $sql_txt .= ', module_profile_item_active=1';

        foreach ($fields as $f)
        {
            $sql_txt .= ', `'.$f['COLUMN_NAME'].'`=?'.$f['dev'];
            $sql_ar[] = trim($f['value']);
        }

        $sql = 'INSERT INTO ?_module_profile_items SET '.$sql_txt.', module_profile_item_date_add=NOW()';
        $this->mysqlquery($sql, $sql_ar);
        return $this->last_insert_id();
    }

    public function testUserEmail($email)
    {
        $sql = 'SELECT * FROM ?_module_profile_items WHERE LOWER(module_profile_item_email)=?s';
        $q = $this->query($sql, array(mb_strtolower($email, 'utf-8')));
        return ($q['module_profile_item_id']>0 ? true : false);
    }

    public function getProfileDataByID($id)
    {
        $sql = 'SELECT * FROM ?_module_profile_items WHERE module_profile_item_id=?i';
        return $this->query($sql, array($id));
    }

    public function getProfileDataByLogin($email)
    {
        $sql = 'SELECT * FROM ?_module_profile_items WHERE LOWER(module_profile_item_email)=?s';
        return $this->query($sql, array(mb_strtolower($email, 'utf-8')));
    }

    #-- функции для работы с дополнительными полями
    public function getDopFieldsTable($data = array())
    {
        $fields = array();
        $sql = 'SELECT COLUMN_NAME, COLUMN_TYPE, COLUMN_COMMENT FROM information_schema.columns WHERE TABLE_NAME="?_module_profile_items" AND TABLE_SCHEMA=?s AND COLUMN_NAME LIKE "p_%"';
        $res = $this->mysqlquery($sql, array($this->db_getDbName()));
        while ($r = $this->fetchOne($res))
        {
            $r['value'] = (isset($data[$r['COLUMN_NAME']]) ? $data[$r['COLUMN_NAME']] : '');
            $r = array_merge($r, $this->getFieldType($r));
            $fields[$r['COLUMN_NAME']] = $r;
        }
        return $fields;
    }

    private function getFieldType($r)
    {
        $data = array();

        #-- вид поля в админке
        if (preg_match('~^(.+)\|([a-z]+)$~isU', $r['COLUMN_COMMENT'], $ar))
        {
            $title = trim($ar[1]);
            $type = trim($ar[2]);
        }
        $name = $r['COLUMN_NAME'];
        $value = trim($r['value']);

        $data['title'] = $title;
        $data['f_type'] = $type;
        $data['is_need'] = (preg_match('~\*~', $title)) ? 1 : 0;
        $class_e = ($data['is_need']>0) ? ' profile-field-is-need-00 profile-field-00' : ' profile-field-00';
        switch ($type)
        {
            case 'text':
                $data['field'] = '<input type="text" id="p-' . $name . '" class="admin-field-style-text' . $class_e . '" name="' . $name . '" value="' . htmlspecialchars($value) . '" />';
                $data['dev'] = 's';
                break;

            case 'textarea':
                $data['field'] = '<textarea id="p-' . $name . '" class="admin-field-style-textarea' . $class_e . '" name="' . $name . '">' . $value . '</textarea>';
                $data['dev'] = 's';
                break;

            case 'radio':
                if (preg_match_all("~'(.+)'~isU", $r['COLUMN_TYPE'], $ar))
                {
                    $data['field'] = '';
                    foreach ($ar[1] as $a)
                    {
                        $data['field'][] = '<input type="radio" id="p-' . $name . '" class="admin-field-style-radio" name="' . $name . '" value="' . htmlspecialchars($a) . '"' . ($value==$a ? ' checked' : '') . ' /> - ' . $a;
                    }
                    $data['dev'] = 's';
                }
                break;

            case 'checkbox':
                $data['field'] = '<input type="checkbox" id="p-' . $name . '" class="admin-field-style-checkbox" name="' . $name . '" value="1"' . ($value ? ' checked' : '') . '/> - Да/Нет';
                $data['dev'] = 'i';
                break;

            case 'select':
                if (preg_match_all("~'(.+)'~isU", $r['COLUMN_TYPE'], $ar))
                {
                    $data['field'][] = '<select id="p-' . $name . '" class="admin-field-style-select" name="' . $name . '">';
                    $data['field'][] = '<option value="">---</option>';
                    foreach ($ar[1] as $a)
                    {
                        $data['field'][] = '<option value="' . htmlspecialchars($a) . '"' . ($a==$value?' selected':'') . '>' . $a . '</option>';
                    }
                    $data['field'][] = '</select>';
                    $data['dev'] = 's';
                }
                break;

            case 'dtext':
                $value = ($value>0) ? date('d.m.Y', strtotime($value)) : '';
                $data['field'] = '<input type="text" id="p-' . $name . '" class="admin-field-style-dtext" name="' . $name . '" value="' . $value . '" />';
                $data['dev'] = 'd';
                break;

            case 'file':
                $data['field'] = '<input type="file" id="p-' . $name . '" class="admin-field-style-file" name="' . $name . '" value="' . htmlspecialchars($value) . '" />';
                $data['dev'] = 's';
                break;

            default:
                $data['field'] = '<i>Тип поля задан неверно!</i>';
        }
        return $data;
    }

    public function deleteProfileItem($tm_id, $uid)
    {
        $sql = 'DELETE FROM ?_module_profile_items WHERE module_profile_item_type=?s AND module_profile_item_id=?i';
        $this->mysqlquery($sql, array($tm_id, $uid));
    }

    public function activeProfileItem($tm_id, $uid)
    {
        $sql = 'UPDATE ?_module_profile_items SET module_profile_item_active=NOT(module_profile_item_active) WHERE module_profile_item_type=?s AND module_profile_item_id=?i';
        $this->mysqlquery($sql, array($tm_id, $uid));
    }

    public function getLastSeenItemsList($profile_id)
    {
        $items = array();
        $sql = 'SELECT * FROM ?_module_profile_catalog_seen WHERE module_profile_id=?i ORDER BY module_profile_catalog_seen_date_update DESC LIMIT 3';
        $res = $this->mysqlquery($sql, array($profile_id));
        while ($r = $this->fetchOne($res))
        {
            $sql = 'SELECT * FROM ?_module_c_catalog_items WHERE module_list_item_id=?i';
            $data = $this->query($sql, array($r['module_profile_catalog_item_id']));
            foreach ($data as $k=>$d)
                if (preg_match('~(.+\/)(.+)\.(jpg|jpeg|gif|png|bmp)$~is', $d, $ar))
                {
                    $data[$k.'_data']['dirname'] = $ar[1];
                    $data[$k.'_data']['name'] = $ar[2] . '.' . $ar[3];
                }
                elseif (preg_match('~^\d{4}\-\d{2}\-\d{2}~', $d, $ar))
                    $data[$k] = strtotime($d);

            $prices = array();
            $prices[] = ($data['f_p_act'] && $data['f_p_see']) ? $data['f_price'] : '';
            $prices[] = ($data['f_p2_act'] && $data['f_p2_see']) ? $data['f_price2'] : '';
            $prices[] = ($data['f_p3_act'] && $data['f_p3_see']) ? $data['f_price3'] : '';
            $prices[] = ($data['f_p4_act'] && $data['f_p4_see']) ? $data['f_price4'] : '';
            $data['price_mass'] = array_filter($prices, "filter_price_mass");

            $items[] = $data;
        }
        return $items;
    }

    public function clearStatSeenItems($sess)
    {
        $sql = 'SELECT COUNT(*) as cnt FROM ?_module_profile_catalog_seen WHERE module_profile_seen_session=?s';
        $q = $this->query($sql, array($sess));
        $cnt = intval($q['cnt']);

        if ($cnt>3)
        {
            $sql = 'DELETE FROM ?_module_profile_catalog_seen WHERE module_profile_seen_session=?s ORDER BY `module_profile_catalog_seen_date_update` ASC LIMIT ?i';
            $this->mysqlquery($sql, array( $sess, $cnt-3 ));
        }
    }

/*
    public function getProfileDataByProfileId($uid)
    {
        $sql = 'SELECT * FROM ?_profiles WHERE profile_id=?i';
        return $this->query($sql, array($uid));
    }

    public function getUsersList()
    {
        $sql = 'SELECT * FROM ?_profiles WHERE 1=1 ORDER BY profile_login';
        return $this->fetchAll($sql, array());
    }

    public function saveNewPwd($uid, $pwd)
    {
        $sql = 'UPDATE ?_profiles SET profile_pwd=?s WHERE profile_id=?i';
        $this->mysqlquery($sql, array($pwd, $uid));
    }

    public function deleteProfileById($uid)
    {
        $user = $this->getProfileDataByProfileId($uid);
        $sql = 'DELETE FROM ?_profiles WHERE profile_id=?i';
        $this->mysqlquery($sql, array($uid));
        return $user;
    }

    public function updateProfile($uid, $user)
    {
        if ($user['g']>0)
        {
            $sql = 'UPDATE ?_profiles SET profile_group=?i, profile_login=?s, profile_active=?i, profile_date_update=NOW() WHERE profile_id=?i';
            $this->mysqlquery($sql, array($user['g'], $user['l'], $user['a'], $uid));
        }
        else
        {
            $sql = 'UPDATE ?_profiles SET profile_login=?s, profile_active=?i, profile_date_update=NOW() WHERE profile_id=?i';
            $this->mysqlquery($sql, array($user['l'], $user['a'], $uid));
        }
    }

    public function insertProfile($user)
    {
        $sql = 'INSERT INTO ?_profiles SET profile_group=?i, profile_login=?s, profile_pwd=?s, profile_active=?i, profile_date_update=NOW()';
        $this->mysqlquery($sql, array($user['g'], $user['l'], $user['p'], $user['a']));
        return $this->last_insert_id();
    }
*/
}
?>