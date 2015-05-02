<?php
if(!defined("S_INC") || S_INC!==true)die();

#-- самый первый класс, с которого все запускается. Управляющий класс.

class _User extends ExtController
{

    public $sql=null;
    public $item_id=0;
    protected $app=null;
    const C_NAME = '_User';
    const C_TITLE = '---';
    const C_DESC = '-';
    const C_ISPUB = 0;

    public function __construct()
    {
        global $APP;
        $this->app = $APP;
        $this->sql = new UserSql();
        $this->action = (isset($_REQUEST['admin_action'])) ? $_REQUEST['admin_action'] : '';
    }

    #-- Функция возвращает контент
    private function getContent()
    {
        $this->actionPopup();
        $this->loadModuleAdminProfileBlock();
    }

    public function loadPopupAdminBlockEdit()
    {
        return $this->getContent();
    }

    private function loadModuleAdminProfileBlock()
    {
        if (!isset($_SESSION['_SITE_']['_User']['dt1']))
            $_SESSION['_SITE_']['_User']['dt1'] = date('Y-m-d', time()-7*24*3600);
        if (!isset($_SESSION['_SITE_']['_User']['dt2']))
            $_SESSION['_SITE_']['_User']['dt2'] = date('Y-m-d');

        $users = $this->sql->getUsersList();
        $stats = $this->sql->getAdminStatList($_SESSION['_SITE_']['_User']['dt1'], $_SESSION['_SITE_']['_User']['dt2']);

        $is_super = ($_SESSION['_SITE_']['userdata']['user_group']==1) ? 1 : 0;
        if ($is_super)
            $stat_t = $this->ApplyTemplateAdmin('block_profile_stats.php', array('stats'=>$stats, 'cl'=>$this->getClassName(), 'tm_id'=>$this->tm_id), $this->getClassName());

        print $this->ApplyTemplateAdmin('block_profile.php', array('users'=>$users, 'stat_t'=>$stat_t, 'is_super'=>$is_super, 'cl'=>$this->getClassName(), 'tm_id'=>$this->tm_id ), $this->getClassName());
    }

    private function actionPopup()
    {
        switch ($this->action)
        {
            case 'filter_stat':
                if ($_SESSION['_SITE_']['userdata']['user_group']==1)
                {
                    $_SESSION['_SITE_']['_User']['dt1'] = $_REQUEST['dt1'];
                    $_SESSION['_SITE_']['_User']['dt2'] = $_REQUEST['dt2'];
                    $stats = $this->sql->getAdminStatList($_SESSION['_SITE_']['_User']['dt1'], $_SESSION['_SITE_']['_User']['dt2']);
                    print $this->ApplyTemplateAdmin('block_profile_stats.php', array('stats'=>$stats, 'cl'=>$this->getClassName(), 'tm_id'=>$this->tm_id), $this->getClassName());
                }
                exit;

            case 'edit_user_save':
                foreach ($_REQUEST['user'] as $uid=>$u)
                {
                    if (!is_numeric($uid))
                        continue;

                    $this->item_id = $uid;

                    $g = intval($u['group']);
                    $l_h = $u['login_hide'];
                    $l = (!isset($u['login']) ? $l_h : substr(trim($u['login']), 0, 50));
                    $p = $u['pwd'];
                    $a = intval($u['active']);
                    $d = intval($u['delete']);

                    #-- если пользователь отмечен на удаление грохаем его
                    if ($d>0 && $_SESSION['_SITE_']['is_adm']==1 && $_SESSION['_SITE_']['userdata']['user_group']==1 && $this->item_id>0 && $this->item_id!=$_SESSION['_SITE_']['userdata']['user_id'])
                    {
                      $user = $this->sql->deleteUserById($this->item_id);
                      $this->app->main_с_saveAdminAction('delete', 'Пользователи', $this->action, 'Пользователь '.$user['user_login'].' (ID:'.$this->item_id.') удален');
                    }
                    elseif ($_SESSION['_SITE_']['is_adm']==1 && $_SESSION['_SITE_']['userdata']['user_group']==1 && preg_match('~^[а-яa-z\d\_\-]+$~iUu', $l))
                    {
                        #-- проверяем пользователя на существование (если было изменения или добавление), если введенного логина нет, то создаем/редактируем чувака
                        $user = ($l != $l_h && $l_h!='') ? $this->sql->getUserDataByLogin($l) : array();
                        $user2 = $this->sql->getUserDataByUserId($this->item_id);

                        if (empty($user))
                        {
                            if ( $this->item_id > 0 && !empty($user2))
                            {
                                $this->sql->updateUser($this->item_id, array('g'=>$g, 'l'=>$l, 'a'=>$a));
                                if ($p!="")
                                    $this->sql->saveNewPwd($this->item_id, $this->hashPwd($p));
                                $this->app->main_с_saveAdminAction('update', 'Пользователи', $this->action, 'Профиль пользователя '.$l.' (ID:'.$this->item_id.') изменен' . ($p!=""?' + изменен его пароль':''));

                                if ($this->item_id==$_SESSION['_SITE_']['userdata']['user_id'])
                                {
                                    if ($g>0)
                                        $_SESSION['_SITE_']['userdata']['user_group'] = $g;
                                    $_SESSION['_SITE_']['userdata']['user_login'] = $l;
                                    $_SESSION['_SITE_']['userdata']['user_active'] = $a;
                                }
                            }
                            elseif ($p!="")
                            {
                                $this->item_id = $this->sql->insertUser(array('g'=>$g, 'l'=>$l, 'p'=>$this->hashPwd($p), 'a'=>1));
                                $this->app->main_с_saveAdminAction('insert', 'Пользователи', $this->action, 'Добавлен пользователь '.$l.' (ID:'.$this->item_id.')');
                            }
                        }
                    }
                }
                headerTo($_SESSION['_SITE_']['back_url']);

            case 'save_profile':
                if ($_SESSION['_SITE_']['is_adm']==1 && $_SESSION['_SITE_']['userdata']['user_id']>0)
                {
                    $pwd_old = trim($_REQUEST['pwd_old']);
                    $pwd_new1 = trim($_REQUEST['pwd_new1']);
                    $pwd_new2 = trim($_REQUEST['pwd_new2']);
                    #-- меняем пароль у авторизованного пользователя (если возможно)
                    if ($pwd_old!='' && $pwd_new1!='' && $pwd_new1==$pwd_new2)
                    {
                        #-- проверяем верен ли старый пароль, если верен, то меняем пароль
                        $user = $this->sql->getUserDataByLogin($_SESSION['_SITE_']['userdata']['user_login']);
                        if ($user['user_pwd'] == $this->hashPwd($pwd_old))
                        {
                            $this->sql->saveNewPwd($_SESSION['_SITE_']['userdata']['user_id'], $this->hashPwd($pwd_new1));
                            $this->app->main_с_saveAdminAction('update', 'Пользователи', $this->action, 'Изменен пароль');
                        }
                    }
                }
                headerTo($_SESSION['_SITE_']['back_url']);

            case 'auth_test':
                sleep(1);
                $l = substr(trim($_REQUEST['l']), 0, 50);
                $user = $this->sql->getUserDataByLogin($l);
                if ($user['user_pwd'] == $this->hashPwd($_REQUEST['p']))
                    print 'ok';
                exit;

            case 'login_auth':
                $login = (isset($_COOKIE['user_l']) && $_COOKIE['user_l']!='') ? $_COOKIE['user_l'] : $_REQUEST['user_login'];
                $pwd = (isset($_COOKIE['user_p']) && $_COOKIE['user_p']!='') ? $_COOKIE['user_p'] : $this->hashPwd($_REQUEST['user_pwd']);

                #-- пытаемся авторизовать пользователя
                $d_user = $this->sql->getUserDataByLogin($login);
                if ($d_user['user_id'] > 0 && $d_user['user_pwd'] == $pwd && $d_user['user_active']>0)
                {
                    unset($d_user['user_pwd']);
                    $_SESSION['_SITE_']['userdata'] = $d_user;
                    $_SESSION['_SITE_']['is_adm'] = 1;
                    $this->app->main_с_saveAdminAction('auth', 'Пользователи', $this->action, 'Авторизация');

                    if (isset($_REQUEST['is_remember']) && $_REQUEST['is_remember']==1)
                    {
                      setcookie('user_l', $login, time()+3600*364, '/');
                      setcookie('user_p', $this->hashPwd($_REQUEST['user_pwd']), time()+3600*364, '/');
                    }
                }
                else
                {
                    $_COOKIE['user_l'] = $_COOKIE['user_p'] = '';
                    setcookie('user_l', '', time()-666, '/');
                    setcookie('user_p', '', time()-666, '/');
                    unset($_COOKIE['user_l']);
                    unset($_COOKIE['user_p']);
                }
                headerTo($_SESSION['_SITE_']['back_url']);

            default:
        }
    }

    protected function hashPwd($pwd)
    {
        return md5( strrev(substr($pwd, 2)) . 'D2' . substr($pwd, 0, -3) . '!' );
    }

    private function __clone() {}

    public function __destruct() {$this->sql->disconnect();}

    public function getClassName() { return self::C_NAME; }
    public function getClassTitle() { return self::C_TITLE; }
    public function getClassDesc() { return self::C_DESC; }
    public function getClassIsPub() { return self::C_ISPUB; }

}