<?php
if(!defined("S_INC") || S_INC!==true)die();

#-- самый первый класс, с которого все запускается. Управляющий класс.

class Profile extends ExtController
{

    protected $sql=null;
    public $tm_id='';
    public $item_id=0;
    public $tpl='';
    public $func='';
    public $action='';
    protected $app=null;
    const C_NAME = 'Profile';
    const C_TITLE = 'Профиль зарегистрированного пользователя';
    const C_DESC = '-';
    const C_ISPUB = 0;

    public function __construct()
    {
        global $APP;
        $this->app = $APP;
        $this->sql = new ProfileSql();
        $this->action = (isset($_REQUEST['a'])) ? $_REQUEST['a'] : '';
        $this->page = (isset($_REQUEST['p']) && $_REQUEST['p']>0) ? intval($_REQUEST['p']) : 1;
    }

    #-- Функция возвращает контент
    private function getContent()
    {
        if (!empty($this->tpl))
        {
            $login = (isset($_COOKIE['profile_l']) && $_COOKIE['profile_l']!='') ? $_COOKIE['profile_l'] : '';
            $pwd = (isset($_COOKIE['profile_p']) && $_COOKIE['profile_p']!='') ? $_COOKIE['profile_p'] : '';

            #-- пытаемся авторизовать пользователя
            if ($login!='' && $pwd!='')
            {
                $d_user = $this->sql->getProfileDataByLogin($login);
                if ($d_user['module_profile_item_id']>0 && $d_user['module_profile_item_pwd']==$pwd && $d_user['module_profile_item_active']>0)
                {
                    unset($d_user['module_profile_item_pwd']);
                    $_SESSION['_SITE_']['profiledata'] = $d_user;
                }
            }

            $content = $this->ApplyTemplate($this->sql->p_data['module_profile_tpl_auth'], array(), $this->getClassName());
        }
        elseif ($this->action == 'reg_form' && !empty($this->sql->p_data['module_profile_tpl_reg']))
        {
            $content = $this->ApplyTemplate($this->sql->p_data['module_profile_tpl_reg'], array('fields'=>$this->sql->getDopFieldsTable($_SESSION['_SITE_']['profiledata']['fields'])), $this->getClassName());
            unset($_SESSION['_SITE_']['profiledata']['message']);
            unset($_SESSION['_SITE_']['profiledata']['fields']);
        }
        elseif ($this->action == 'forgot_pwd')
        {
            $content = $this->ApplyTemplate('p_forgot_pwd.php', array(), $this->getClassName());
        }
        elseif ($this->action == 'forgot_pwd_code')
        {
            $content = $this->ApplyTemplate('p_forgot_pwd_code.php', array('code'=>substr($_REQUEST['code'],0,10), 'email'=>substr($_REQUEST['e'],0,100)), $this->getClassName());
        }
        elseif ( $_SESSION['_SITE_']['profiledata']['module_profile_item_id']>0 )
        {
            switch ($this->action)
            {
                case 'index':
                    global $pre_pay;
                    #-- очищаем информацию о ранее просмотренных товарах
                    $bask = new Basket();
                    $this->sql->clearStatSeenItems($bask->getCurSessId());

                    $orders = $this->sql->getOrdersProfileList('catalog',  $_SESSION['_SITE_']['profiledata']['module_profile_item_id']);

//                    $items = $this->sql->getLastSeenItemsList($_SESSION['_SITE_']['profiledata']['module_profile_item_id']);
//                    $seen = $this->ApplyTemplate('catalog_seen_list_auth.php', array('catalog'=>$items));

                    $content = $this->ApplyTemplate('p_orders.php', array('pre_pay'=>$pre_pay, 'orders'=>$orders), $this->getClassName());
                    unset($_SESSION['_SITE_']['profiledata']['message_p']);
                    break;

                case 'edit':
                    $profile = $this->sql->getProfileDataByID($_SESSION['_SITE_']['profiledata']['module_profile_item_id']);
                    $fields = $this->sql->getDopFieldsTable($profile);

//                    $items = $this->sql->getLastSeenItemsList($_SESSION['_SITE_']['profiledata']['module_profile_item_id']);
//                    $seen = $this->ApplyTemplate('catalog_seen_list_auth.php', array('catalog'=>$items));

                    $content = $this->ApplyTemplate('p_edit.php', array('profile'=>$profile, 'fields'=>$fields), $this->getClassName());
                    unset($_SESSION['_SITE_']['profiledata']['message_p']);
                    break;

                case 'edit_profile_save':
                    unset($_SESSION['_SITE_']['profiledata']['message_p']);

                    $fields = $this->sql->getDopFieldsTable($_REQUEST);

                    if ( ! $this->extc_test_add_catalog_fields($fields) )
                        $_SESSION['_SITE_']['profiledata']['message_p']['error']['all'] = 1;

                    if ( ! $_SESSION['_SITE_']['profiledata']['message_p']['error'] )
                    {
                        $this->sql->saveProfileItemsData($this->tm_id, $_SESSION['_SITE_']['profiledata']['module_profile_item_id'], $fields);

                        $d_user = $this->sql->getProfileDataByLogin($_SESSION['_SITE_']['profiledata']['module_profile_item_email']);
                        unset($d_user['module_profile_item_pwd']);
                        $_SESSION['_SITE_']['profiledata'] = $d_user;

                        $_SESSION['_SITE_']['profiledata']['message_p']['save'] = 1;

                        #-- изменяем пароль
                        if ($_REQUEST['pass']['p0']!='')
                        {
                            $profile = $this->sql->getProfileDataByID($_SESSION['_SITE_']['profiledata']['module_profile_item_id']);

                            if ($_REQUEST['pass']['p0'] == $_REQUEST['pass']['p1'])
                                $_SESSION['_SITE_']['profiledata']['message_p']['error']['pass_old1'] = 1;
                            elseif ($this->hashPwd($_REQUEST['pass']['p0']) != $profile['module_profile_item_pwd'])
                                $_SESSION['_SITE_']['profiledata']['message_p']['error']['pass_old2'] = 1;
                            elseif ( strlen($_REQUEST['pass']['p1'])<6)
                                $_SESSION['_SITE_']['profiledata']['message_p']['error']['pass_new1'] = 1;
                            elseif ( $_REQUEST['pass']['p1']!=$_REQUEST['pass']['p2'] )
                                $_SESSION['_SITE_']['profiledata']['message_p']['error']['pass_new2'] = 1;

                            if ( ! $_SESSION['_SITE_']['profiledata']['message_p']['error'] )
                            {
                                $this->sql->saveProfileItemPassword($_SESSION['_SITE_']['profiledata']['module_profile_item_id'], $this->hashPwd($_REQUEST['pass']['p1']));
                                $_SESSION['_SITE_']['profiledata']['message_p']['save_pass'] = 1;
                            }
                        }
                    }

                    headerTo('/profile/?cl='.$this->getClassName().'&tm='.$this->tm_id.'&a=edit');

                case 'pay':
                    include_once(S_ROOT . '/includes/int2text.inc.php');
                    $basket = new Basket();
                    $item_id = intval($_REQUEST['item_id']);
                    $data = $basket->getOrderDataByID($item_id, intval($_SESSION['_SITE_']['profiledata']['module_profile_item_id']));
                    global $pre_pay;
                    print $this->ApplyTemplate('/profile/p_pay30.php', array('pre_pay'=>$pre_pay, 'data'=>$data));
                    exit;

                default:
                    header_error_404();
                    $content = $this->ApplyTemplate('404.php');
            }
        }
        else
            $content = $this->ApplyTemplate('auth_err.php', array(), $this->getClassName());

        return $content;
    }

    public function loadPopupAdminBlockEdit()
    {
        #-- инициирует массив данных модуля профиля: $this->sql->p_data
        $this->sql->getProfileData($this->tm_id);

        $this->actionPopup();

        $cnt_on_p = 10;
        $users_c = $this->sql->getUsersCnt($this->tm_id);
        $users = $this->sql->getUsersList($this->tm_id, $this->page, $cnt_on_p);
        $this->extc_getPagerListAdminAjax($users_c, $cnt_on_p, $this->page, 'nextPageList(#PAGE#);');
        $pages_u = $this->ext_page_text;

        $fields = $this->sql->getDopFieldsTable();

        if ($_REQUEST['uid'])
        {
            $status = $this->sql->getStatusesListP();
            $orders = $this->sql->getOrdersProfileList('catalog', $_REQUEST['uid']);
        }

        print $this->ApplyTemplateAdmin('edit.php', array('users'=>$users, 'fields'=>$fields, 'data'=>$this->sql->p_data, 'pages_u'=>$pages_u, 'orders'=>$orders, 'status'=>$status), $this->getClassName());
    }

    public function __toString()
    {
        #-- инициирует массив данных модуля профиля: $this->sql->p_data
        $this->sql->getProfileData($this->tm_id, $this->tpl, $this->func);

        $this->actionPopup();
        return $this->getContent();
    }

    private function actionPopup()
    {
        switch ($this->action)
        {
            #-- здесь происходит изменение пароля по ссылке из письма
            case 'forgot_pwd_change':
                unset($_SESSION['_SITE_']['profiledata']['message']);

                $code = substr($_REQUEST['code'], 0, 10);
                $email = substr($_REQUEST['e'], 0, 100);

                if ( ! preg_match('~.+@.+\..+~i', $email) )
                    $_SESSION['_SITE_']['profiledata']['message']['error']['email_er'] = 1;
                elseif ( ! $this->sql->testUserEmail($email) )
                    $_SESSION['_SITE_']['profiledata']['message']['error']['email_no'] = 1;
                elseif (strlen($_REQUEST['p1'])<6)
                    $_SESSION['_SITE_']['profiledata']['message']['error']['pass_len'] = 1;
                elseif ($_REQUEST['p1']!='' && $_REQUEST['p1']!=$_REQUEST['p2'])
                    $_SESSION['_SITE_']['profiledata']['message']['error']['pass_eq'] = 1;

                if ( ! $_SESSION['_SITE_']['profiledata']['message']['error'] )
                {
                    if ( $this->sql->updateProfilePwdEmailCode($this->tm_id, $email, $code, $this->hashPwd($_REQUEST['p1'])) )
                        $_SESSION['_SITE_']['profiledata']['message']['success'] = 1;
                    else
                        $_SESSION['_SITE_']['profiledata']['message']['error']['email_hash'] = 1;
                }

                headerTo('/profile/?cl=' . $this->getClassName() . '&tm=' . $this->tm_id . '&a=forgot_pwd_code&e=' . $email . '&code=' . $code);

            #-- здесь высылается ссылка для смены пароля на указанный E-mail
            case 'forgot_pwd_send':
                unset($_SESSION['_SITE_']['profiledata']['message']);
                $_SESSION['_SITE_']['profiledata']['message']['email'] = $_REQUEST['email'];
                $email = substr($_REQUEST['email'], 0, 100);
                if ( ! preg_match('~.+@.+\..+~i', $email) )
                    $_SESSION['_SITE_']['profiledata']['message']['error']['email_er'] = 1;
                elseif ( ! $this->sql->testUserEmail($email) )
                    $_SESSION['_SITE_']['profiledata']['message']['error']['email_no'] = 1;

                if ( ! $_SESSION['_SITE_']['profiledata']['message']['error'] )
                {
                    $code = substr($this->hashPwd('P_'.$email.time()), 5, 10);
                    $this->sql->updateProfileCodeByEmail($this->tm_id, $email, $code);

                    $subj = 'Запрос на восстановление пароля на сайте ' . S_HOST . N;
                    $text = 'Вы запросили восстановление пароля на сайте ' . S_HOST . N;
                    $text.= 'Пройдите по ссылке, для ввода нового пароля: http://' . S_HOST .'/profile/?cl=' . $this->getClassName() . '&tm=' . $this->tm_id . '&a=forgot_pwd_code&e=' . $email . '&code=' . $code . N . N;
                    $text.= 'Если Вы не заказывали изменение пароля, просто проигнорируйте это письмо!';

                    $this->extc_sendmail($email, $subj, $text, 'Givena.ru <'.$this->sql->p_data['module_profile_email_from'].'>');
                    $_SESSION['_SITE_']['profiledata']['message']['success_send'] = 1;
                }

                headerTo('/profile/?cl=Profile&tm=form&a=forgot_pwd');

            #-- здесь происходит запрос на корректность регистрации
            case 'reg_test':
                $err = array();
                foreach ($_REQUEST['reg'] as $k=>$v)
                    $data[$k] = trim($v);

                if (preg_match('~индекс[\W]+адрес[\W]+дом[\W]+квартира~iu',$data['p_adress']))
                    $err['all'] = 1;

                if ( $data['e']=='' || $data['p']=='' || $data['g']!="")
                    $err['all'] = 1;
                if ( $data['p']!='' && $data['p']!=$data['p2'])
                    $err['pass'] = 1;
                elseif ( $data['p']!='' && strlen($data['p'])<6)
                    $err['pass2'] = 1;
                if ( ! preg_match('~.+@.+\..+~i',$data['e']) || $this->sql->testUserEmail($data['e']))
                    $err['email'] = 1;

                $_REQUEST = $data;
                $fields = $this->sql->getDopFieldsTable($data);
                if ( ! $this->extc_test_add_catalog_fields($fields) )
                    $err['all'] = 1;

                if ($err)
                    print json_encode($err);
                else
                    print json_encode(array('ok'));
                exit;

            case 'reg_confirm':
                unset($_SESSION['_SITE_']['profiledata']['message']);

                $code = substr($_REQUEST['code'], 0, 10);
                $email = substr($_REQUEST['e'], 0, 100);

                if ( $this->sql->updateProfileActiveEmailCode($this->tm_id, $email, $code) )
                    $_SESSION['_SITE_']['profiledata']['message']['success'] = 13;
                else
                    $_SESSION['_SITE_']['profiledata']['message']['error']['confirm'] = 1;
                headerTo('/profile/?cl='.$this->getClassName().'&tm='.$this->tm_id.'&a=reg_form');

            #-- здесь происходит регистрация пользователя
            case 'reg':
                unset($_SESSION['_SITE_']['profiledata']['fields']);

                $data = $_REQUEST;
                $_SESSION['_SITE_']['profiledata']['fields'] = $data;
                foreach ($_REQUEST['reg'] as $k=>$v)
                {
                    $data[$k] = trim($v);
                    $_SESSION['_SITE_']['profiledata']['fields'][$k] = $data[$k];
                }

                $this->regUserData($data);

                headerTo('/profile/?cl='.$this->getClassName().'&tm='.$this->tm_id.'&a=index');

            case 'auth_test':
                sleep(1);
                $l = substr(trim($_REQUEST['l']), 0, 50);
                $user = $this->sql->getProfileDataByLogin($l);
                if ($user['user_pwd'] == $this->hashPwd($_REQUEST['p']))
                    print 'ok';
                exit;

            case 'login':
                unset($_SESSION['_SITE_']['profiledata']);
                foreach ($_REQUEST['auth'] as $k=>$v)
                    $auth[$k] = trim($v);

                $login = (isset($_COOKIE['profile_l']) && $_COOKIE['profile_l']!='') ? $_COOKIE['profile_l'] : $auth['e'];
                $pwd = (isset($_COOKIE['profile_p']) && $_COOKIE['profile_p']!='') ? $_COOKIE['profile_p'] : $this->hashPwd($auth['p']);

                #-- пытаемся авторизовать пользователя
                $d_user = $this->sql->getProfileDataByLogin($login);
                if ($d_user['module_profile_item_id']>0 && $d_user['module_profile_item_pwd']==$pwd && $d_user['module_profile_item_active']>0)
                {
                    unset($d_user['module_profile_item_pwd']);
                    $_SESSION['_SITE_']['profiledata'] = $d_user;

//                    if (isset($auth['r']) && $auth['r']==1)
                    {
                        setcookie('profile_l', $login, time()+3600*364, '/');
                        setcookie('profile_p', $this->hashPwd($auth['p']), time()+3600*364, '/');
                    }
                }
                else
                {
                    $_COOKIE['profile_l'] = $_COOKIE['profile_p'] = '';
                    setcookie('profile_l', '', time()-666, '/');
                    setcookie('profile_p', '', time()-666, '/');
                    unset($_COOKIE['profile_l']);
                    unset($_COOKIE['profile_p']);

                    $_SESSION['_SITE_']['profiledata']['message']['auth_err_login'] = $login;
                }

                headerTo('/profile/?cl='.$this->getClassName().'&a=index&tm='.$this->tm_id);

            case 'logout':
                unset($_SESSION['_SITE_']['profiledata']);
                $_COOKIE['profile_l'] = $_COOKIE['profile_p'] = '';
                setcookie('profile_l', '', time()-666, '/');
                setcookie('profile_p', '', time()-666, '/');
                unset($_COOKIE['profile_l']);
                unset($_COOKIE['profile_p']);
                headerTo('/');

            case 'save':
                $this->sql->saveProfileData($this->tm_id);
                $fields = $this->sql->getDopFieldsTable();
                $this->sql->saveProfileItems($this->tm_id, $fields);
                $this->app->main_с_saveAdminAction('update', self::C_TITLE.' (ID:'.$this->tm_id.')', $this->action, 'Изменение содержимого блока');
                headerTo($_SESSION['_SITE_']['back_url']);

            case 'edit_delete':
                $this->sql->deleteProfileItem($this->tm_id, $_REQUEST['uid']);
                $this->app->main_с_saveAdminAction('update', self::C_TITLE.' (ID:'.$this->tm_id.')', $this->action, 'Удаление пользователя');
                print 'ok';
                exit;

            case 'edit_active':
                $this->sql->activeProfileItem($this->tm_id, $_REQUEST['uid']);
                $this->app->main_с_saveAdminAction('update', self::C_TITLE.' (ID:'.$this->tm_id.')', $this->action, 'Изменение активности пользователя');
                print 'ok';
                exit;

            default:
        }
    }

    public function regUserData($data)
    {
        unset($_SESSION['_SITE_']['profiledata']['message']);

        if ( $data['e']=='' || $data['p']=='' || $data['g']!="")
            $_SESSION['_SITE_']['profiledata']['message']['error']['all'] = 1;
        if ( $data['p']!='' && $data['p']!=$data['p2'])
            $_SESSION['_SITE_']['profiledata']['message']['error']['pass'] = 1;
        elseif ( $data['p']!='' && strlen($data['p'])<6)
            $_SESSION['_SITE_']['profiledata']['message']['error']['pass2'] = 1;
        if ( ! preg_match('~.+@.+\..+~i',$data['e']) || $this->sql->testUserEmail($data['e']))
            $_SESSION['_SITE_']['profiledata']['message']['error']['email'] = 1;

        $fields = $this->sql->getDopFieldsTable($data);
        if ( ! $this->extc_test_add_catalog_fields($fields) )
            $_SESSION['_SITE_']['profiledata']['message']['error']['all'] = 1;

        if (!$_SESSION['_SITE_']['profiledata']['message']['error'])
        {
            $pwd = $data['p'];
            $data['p'] = $this->hashPwd($data['p']);
            $user_id = $this->sql->addNewUserSite($this->tm_id, $data, $fields);
            if ($user_id>0)
            {
                $_SESSION['_SITE_']['profiledata']['message']['success'] = 2;

                $user = $this->sql->getProfileDataByID($user_id);
                $subj = 'Регистрация на сайте ' . S_HOST;
                #-- высылаем сообщение пользователю об успешной регистрации
                if ($user['module_profile_item_code']!='')
                {
                    $code = $user['module_profile_item_code'];//substr($this->hashPwd($this->tm_id.$data['e'].time()), 5, 10);
                    $this->sql->updateProfileCodeByEmail($his->tm_id, $data['e'], $code);

                    $text = 'Для подтверждения регистрации перейдите, пожалуйста по ссылке:' . N;
                    $text.= 'http://' . S_HOST . '/profile/?cl=' . $this->getClassName() . '&tm=' . $this->tm_id . '&a=reg_confirm&e=' . $data['e'] . '&code=' . $code;

                    $_SESSION['_SITE_']['profiledata']['message']['success'] = 1;
                }
                elseif ($user['module_profile_item_active']==0)
                {
                    $text = 'Вы успешно зарегистрированы на сайте ' . S_HOST . N;
                    $text.= 'Администратор ресурса активирует Вашу учетную запись после проверки.' . N;

                    $_SESSION['_SITE_']['profiledata']['message']['success'] = 2;
                }
                elseif ($user['module_profile_item_active']==1)
                {
                    $text = 'Вы успешно зарегистрированы на сайте ' . S_HOST . N;
                    $text.= 'Ваши данные: ' . N;
                    $text.= 'E-mail: ' . $user['module_profile_item_email'] . N;
                    $text.= 'Пароль: ' . $pwd . N;
                    foreach ($fields as $f)
                        $text.= $f['title'] . ': ' . $f['value'] . N;

                    $_SESSION['_SITE_']['profiledata']['message']['success'] = 3;
                }
                $this->extc_sendmail($user['module_profile_item_email'], $subj, $text, 'Givena.ru <'.$this->sql->p_data['module_profile_email_from'].'>');

                #-- высылаем сообщение администратору о регистрации нового пользователя
                if ($this->sql->p_data['module_profile_emails']!='')
                {
                    $text = 'На сайте зарегистрирован новый пользователь:' . N;
                    $text.= 'E-mail: ' . $user['module_profile_item_email'] . N;
                    foreach ($fields as $f)
                        $text.= $f['title'] . ': ' . $f['value'] . N;
                    $emails = explode(',', $this->sql->p_data['module_profile_emails']);
                    foreach ($emails as $e)
                    {
                        $e = trim($e);
                        $this->extc_sendmail($e, 'Регистрация нового пользователя на сайте '.S_HOST, $text, 'Givena.ru <'.$this->sql->p_data['module_profile_email_from'].'>');
                    }
                }

                #-- авторизуем пользователя
                if ($_SESSION['_SITE_']['profiledata']['message']['success']==3)
                {
                    $login = $data['e'];
                    $pwd = $data['p'];

                    #-- пытаемся авторизовать пользователя
                    $d_user = $this->sql->getProfileDataByLogin($login);
                    if ($d_user['module_profile_item_id']>0 && $d_user['module_profile_item_pwd']==$pwd && $d_user['module_profile_item_active']>0)
                    {
                        unset($d_user['module_profile_item_pwd']);
                        $_SESSION['_SITE_']['profiledata'] = $d_user;
                        setcookie('profile_l', $login, time()+3600*364, '/');
                        setcookie('profile_p', $data['p'], time()+3600*364, '/');
                    }
                }
            }
            else
                $_SESSION['_SITE_']['profiledata']['message']['error']['unknow'] = 1;
        }
    }

    protected function hashPwd($pwd)
    {
        return md5( strrev(substr($pwd, 2)) . 'DP2' . substr($pwd, 0, -2) . '!' );
    }

    private function __clone() {}

    public function __destruct() {}

    public function getClassName() { return self::C_NAME; }
    public function getClassTitle() { return self::C_TITLE; }
    public function getClassDesc() { return self::C_DESC; }
    public function getClassIsPub() { return self::C_ISPUB; }

}