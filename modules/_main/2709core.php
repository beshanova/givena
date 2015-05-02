<?php
if(!defined("S_INC") || S_INC!==true)die();

#-- самый первый класс, с которого все запускается. Управляющий класс.

class _Main extends ExtController
{

    public $urls = array();
    public $topic = array();
    public $sql=null;
    private $content_blocks=array();
    public $is_404=false;
    const C_NAME = '_Main';
    const C_TITLE = '---';
    const C_DESC = '-';
    const C_ISPUB = 0;

    public function __construct()
    {
        $this->urls = explode('/',trim($_REQUEST['path'], '/'));
        $this->sql = new MainSql();
        $this->action = (isset($_REQUEST['admin_action'])) ? $_REQUEST['admin_action'] : (isset($_REQUEST['action']) ? $_REQUEST['action'] : '');
    }

    #-- Функция возвращает контент
    function getContent()
    {
        if ($this->action=='search_res')
        {
            $tm_id = substr(str_replace(' ','',$_REQUEST['tm']),0,20);
            $cl = 'Search';

            eval('$obj = new '.ucfirst($cl).'();');
            $obj->tm_id = $tm_id;
            $obj->action = $this->action;
            $this->content_blocks[] = $obj;
        }

        if ($this->action=='adv_search_res')
        {
            $tm_id = substr(str_replace(' ','',$_REQUEST['tm']),0,20);
            $cl = 'Advanced_Search';

            eval('$obj = new '.ucfirst($cl).'();');
            $obj->tm_id = $tm_id;
            $obj->action = $this->action;
            //printarray($obj);
            $this->content_blocks[] = $obj;
        }

        if ($this->action=='abc_search_res')
        {
            $tm_id = 'abc_search';
            $cl = 'ABC_search';

            eval('$obj = new '.ucfirst($cl).'();');
            $obj->tm_id = $tm_id;
            $obj->action = $this->action;
            $this->content_blocks[] = $obj;
        }
        if ( ! $this->is_404 )
        {
            $_SESSION['_SITE_']['topic'] = $this->topic;
            return (sizeof($this->content_blocks)>0) ? implode(N,$this->content_blocks) : '';
        }
        else
            return $this->ApplyTemplate('404.php');
    }

    function __toString()
    {
        $tm_id = substr(str_replace(' ','',$_REQUEST['tm']),0,20);

        if (in_array($this->action, array('add2basket','basket_show','del2basket','basket_go')))
        {
            $obj = new Basket();
            $obj->tm_id = $tm_id;
            $obj->action = $this->action;
            $obj->updateBasket();
        }

//        if ($this->action!='search_res')
        {
            $this->getCurTopic();
        }

        if ($this->action=='login_auth' || $this->action=='auth_test' || (!isset($_SESSION['_SITE_']['is_adm']) || !$_SESSION['_SITE_']['is_adm']) && (isset($_COOKIE['user_l']) && isset($_COOKIE['user_p']) && $_COOKIE['user_l']!=''))
        {
            if (!isset($_REQUEST['admin_action']))
                $_REQUEST['admin_action'] = 'login_auth';
            #-- пытаемся авторизовать пользователя
            $this->loadModuleAdmin('_User', 0);
        }
        else
        #-- управление всплывающими окнами
        if (isset($_REQUEST['ajax']) && $_REQUEST['ajax'] && $_SESSION['_SITE_']['is_adm']>0)
        {
            $cl = substr(str_replace(' ','',$_REQUEST['cl']),0,20);
            if (preg_match('~^_main$~i',$cl))
            {
                $this->actionPopup();
                if (isset($_REQUEST['d']) && $_REQUEST['d']=='params')
                    $this->loadModuleAdminParamsBlock();
                elseif (isset($_REQUEST['d']) && $_REQUEST['d']=='fileman')
                    $this->loadModuleAdminFilemanBlock();
                elseif (isset($_REQUEST['d']) && $_REQUEST['d']=='about')
                    $this->loadModuleAdminAboutBlock();
                else
                    $this->loadModuleAdminAddBlock();
            }
            elseif ($tm_id!='' && $cl!='')
                $this->loadModuleAdmin($cl, $tm_id, intval($_REQUEST['item_id']));
            else
                print 'Ошибка!';
            exit;
        }

        return $this->ApplyTemplate('main.php', array('CONTENT'=>$this->getContent()), $this->getClassName());
    }

    #-- по текущему урлу находим топик
    function getCurTopic()
    {
        if ( !isset($this->topic[0]) || !$this->topic[0] )
        {
            $cnt_url = sizeof($this->urls);
            $this->urls = array_reverse($this->urls);
            foreach ($this->urls as $k=>$u)
            {
                $this->topic[$k] = $this->sql->getTopicDataByUrl($u, ($k>0?$this->topic[$k-1]['module_menu_parent_id']:0), intval($_REQUEST['id']));
                if ($k>0 && empty($this->topic[$k])) //$this->topic[$k-1]['module_menu_parent_id']!=$this->topic[$k]['module_menu_id'])
                {
                    header_error_404();
                    $this->is_404 = true;
                }
            }

            if ( ! $this->topic[0] )
            {
                header_error_404();
                $this->is_404 = true;
            }

            #-- Находим для текущего топика список добавленных к нему модулей
            $this->topic[0]['modules'] = $this->sql->getTopicModules($this->topic[0]['topic_id']);

            foreach ($this->topic[0]['modules'] as $m)
            {
                ob_start();
                $this->loadModule($m['topics_module_class'], $m['topics_module_id']);
                $this->content_blocks[] = ob_get_contents();
                ob_end_clean();
            }
        }
    }

    public function getCurUrl()
    {
        $path = '';
        foreach (array_reverse($this->topic) as $t)
            $path .= '/'.$t['module_menu_url'];
        return str_replace('//','/',$path);
    }

    public function loadModule($class, $tm_id='', $tpl='', $func='')
    {
        eval('$obj = new '.ucfirst($class).'();');
        $obj->tm_id = $tm_id;
        $obj->tpl = $tpl;
        if (preg_match('~^f_~i',$func) || $func!="")
            $obj->func = trim($func);

        if (method_exists($obj, 'initVars'))
            $obj->initVars();

        print $obj;
    }

    private function loadModuleAdmin($class, $tm_id='', $item_id=0)
    {
        eval('$obj = new '.ucfirst($class).'();');
        $obj->tm_id = $tm_id;
        if ($item_id>0)
            $obj->item_id = $item_id;

        if (method_exists($obj, 'initVars'))
            $obj->initVars();

        $obj->loadPopupAdminBlockEdit();
    }

    private function loadModuleAdminParamsBlock()
    {
        print $this->ApplyTemplate('/admin/tpl/block_params.php', array());
    }

    private function loadModuleAdminFilemanBlock()
    {
        print $this->ApplyTemplate('/admin/tpl/block_fileman.php', array());
    }

    private function loadModuleAdminAboutBlock()
    {
        print $this->ApplyTemplate('/admin/tpl/block_about.php', array());
    }

    private function loadModuleAdminAddBlock()
    {
        #-- получаем список существующих модулей
        $modules = $this->sql->getModulesList();

        print $this->ApplyTemplate('/admin/tpl/block_add.php', array('modules'=>$modules));
    }

    private function actionPopup()
    {
        switch ($this->action)
        {
            case 'down':
            case 'up':
                $this->sql->saveBlockSort($this->action, intval($_REQUEST['tm']));
                $this->main_с_saveAdminAction('sort', 'Blocks', $this->action);
                headerTo($_SESSION['_SITE_']['back_url']);
            case 'save':
                $cl = strtolower($_REQUEST['type']);
                eval('$obj = new '.ucfirst($cl).'();');
                $obj->tm_id = $this->sql->saveBlockTopic($cl);

                if (method_exists($obj, 'initVars'))
                    $obj->initVars();
                $obj->saveNewBlock();

                $_SESSION['_SITE_']['add_new_block']['id'] = $obj->tm_id;
                $_SESSION['_SITE_']['add_new_block']['cl'] = $cl;

                headerTo($_SESSION['_SITE_']['back_url']);
            case 'save_params':
                $this->sql->saveParamsBlock();
                headerTo($_SESSION['_SITE_']['back_url']);
            case 'delete_page_img':
                $src = $_SESSION['_SITE_']['topic'][0]['page_img'];
                if ($src!="")
                {
                    $_SESSION['_SITE_']['topic'][0]['page_img'] = '';
                    $this->sql->deletePageImg($_SESSION['_SITE_']['topic'][0]['topic_id']);
                    if (is_file(S_ROOT . $src) && file_exists(S_ROOT . $src))
                        unlink(S_ROOT . $src);
                    print 'ok';
                }
                else
                    print 'err';
                exit;
            case 'change_mode':
                if (intval($_SESSION['_SITE_']['is_adm'])>0)
                    $_SESSION['_SITE_']['is_adm'] = ($_SESSION['_SITE_']['is_adm']==1) ? 2 : 1;
                headerTo($_SESSION['_SITE_']['back_url']);
            default:
        }
    }

    public function loadMetaTitle()
    {
        print $_SESSION['_SITE_']['topic'][0]['meta_title'];
    }
    public function loadMetaKeywords()
    {
        print $_SESSION['_SITE_']['topic'][0]['meta_keywords'];
    }
    public function loadMetaDescription()
    {
        print $_SESSION['_SITE_']['topic'][0]['meta_desc'];
    }

    public function main_с_saveAdminAction($type, $block, $action, $comment='')
    {
        $this->sql->main_saveAdminAction($type, $block, $action, $comment);
    }

    public function main_c_getHelpText($class)
    {
        return $this->sql->main_getHelpText($class);
    }

	public function main_c_saveEmailIntoSendmail($email, $name='')
	{
		$this->sql->main_c_saveEmailIntoSendmail($email, $name);
	}

    private function __clone() {}

    public function __destruct() {$this->sql->disconnect();}

    public function getClassName() { return self::C_NAME; }
    public function getClassTitle() { return self::C_TITLE; }
    public function getClassDesc() { return self::C_DESC; }
    public function getClassIsPub() { return self::C_ISPUB; }

}