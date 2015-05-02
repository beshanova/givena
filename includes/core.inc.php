<?php
if(!defined("S_INC") || S_INC!==true)die();

class ExtController
{

    private static $is_adm = 0;
    public $page=1;
    public $ext_page_text='';

    function __construct() {}

    public function ReadTemplate($tpl, $params=array())
    {
        global $APP;
        $handle = fopen($tpl, "r");
        $_cont_ = fread($handle, filesize($tpl));
        fclose($handle);

        // применяет шаблон вывода к данным
        foreach($params as $k => $p)
        {
            if(!is_int($k))
            {
                $cmd = '$'.$k.' = $p;';
                eval($cmd);
            }
        }
        ob_start();
        eval('?>' . $_cont_ . '<?');
        $_cont_ = ob_get_contents();
        ob_end_clean();
        return $_cont_;
    }

    public function ApplyTemplate($tpl, $params=array(), $class='')
    {
        $_cont_ = '';
        $this->is_adm = intval($_SESSION['_SITE_']['is_adm']);

        $class = strtolower($class);

        #-- указывает на активность блока
        if ((!isset($params['is_active']) && $this->is_adm!=1) || (isset($params['is_active']) && intval($params['is_active'])==1 && $this->is_adm!=1) || ($this->is_adm==1) )
        {
            if ($class!='' && $this->IsFileExists(S_ROOT . '/tpl/' . $_SESSION['_SITE_']['theme'] . '/' . $_SESSION['_SITE_']['lang'] . '/' . $class . '/' . $tpl))
            {
                $tpl = S_ROOT . '/tpl/' . $_SESSION['_SITE_']['theme'] . '/' . $_SESSION['_SITE_']['lang'] . '/' . $class . '/' . $tpl;
            }
            elseif ($this->IsFileExists(S_ROOT . '/tpl/' . $_SESSION['_SITE_']['theme'] . '/' . $_SESSION['_SITE_']['lang'] . '/' . $tpl))
            {
                $tpl = S_ROOT . '/tpl/' . $_SESSION['_SITE_']['theme'] . '/' . $_SESSION['_SITE_']['lang'] . '/' . $tpl;
            }
            elseif ($this->IsFileExists(S_ROOT . '/modules/' . $class . '/tpl/' . $_SESSION['_SITE_']['lang'] . '/' . $tpl))
            {
                $tpl = S_ROOT . '/modules/' . $class . '/tpl/' . $_SESSION['_SITE_']['lang'] . '/' . $tpl;
            }
            elseif ($class=='')
            {
                $tpl = S_ROOT . $tpl;
            }
            else
                $tpl = '';

            if ($tpl!='')
            {
                $_cont_ = $this->ReadTemplate($tpl, $params);
            }
            else
                $_cont_ = 'Шаблон не найден!';
        }

        if ($this->is_adm==1 && $class!='' && $class!='_main' && !isset($params['_item_']))
        {
            $_cont_ = $this->AdminBlock($_cont_, $params);
        }

        return $_cont_;
    }

    public function ApplyTemplateAdmin($tpl, $params=array(), $class='')
    {
        $class = strtolower($class);

        if ($this->IsFileExists(S_ROOT . '/modules/' . $class . '/tpl/admin/' . $tpl))
        {
            $tpl = S_ROOT . '/modules/' . $class . '/tpl/admin/' . $tpl;
        }
        else
            $tpl = '';

        if ($tpl!='')
        {
            $_cont_ = $this->ReadTemplate($tpl, $params);
        }
        else
            $_cont_ = 'Шаблон административного интерфейса не найден!';

        return $_cont_;
    }

    public function IsFileExists($file)
    {
        if ( $file!='' && is_file($file) && file_exists($file) )
            return true;
        else
            return false;
    }

    public function AdminBlock($cont, $params=array())
    {
        if ($this->is_adm==1)
            return $this->ApplyTemplate('/admin/tpl/block.php', array('block'=>$cont, 'tm_id'=>$this->tm_id, 'cl'=>$this->getClassName(), 'is_pub'=>$this->getClassIsPub(), 'params'=>$params, 'is_active'=>$params['is_active']), '');
        else
            return ($params['is_active'])?$cont:'';
    }

    public function AdminBlockItem($cont, $params=array())
    {
        if ($this->is_adm==1)
            return $this->ApplyTemplate('/admin/tpl/block_item.php', array('block'=>$cont, 'tm_id'=>$this->tm_id, 'cl'=>$this->getClassName(), 'params'=>$params, 'item_id'=>$params['item_id'], 'is_active'=>$params['is_active']), '');
        else
            return $cont;
    }

    public function loadAdminStyle()
    {
        if ($this->is_adm>0)
        {
            print '<link href="/admin/css/admin.css" rel="stylesheet" type="text/css">'.N;
            print '<link href="/admin/css/popup.css" rel="stylesheet" type="text/css">'.N;
            print '<link href="/admin/css/color.css" rel="stylesheet" type="text/css">'.N;
            print '<!--[if lte IE 8]><link href="/admin/css/ie.css" rel="stylesheet" type="text/css"><![endif]-->'.N;
            print '<script src="/admin/js/popup.js" type="text/javascript"></script>'.N;
            print '<script src="/admin/js/popup_dop.js" type="text/javascript"></script>'.N;
            print '<script src="/admin/js/jquery-ui-1.9.0.min.js" type="text/javascript"></script>'.N;
            print '<script src="/admin/js/jquery-ui-timepicker-addon.js"></script>'.N;
        }
    }

    public function loadAdminPanel()
    {
        if ($this->is_adm>0)
        {
            print $this->ApplyTemplate('/admin/tpl/panel.php', array(), '');
        }
        if ($_SESSION['_SITE_']['is_form_auth'] == 1)
        {
            print $this->ApplyTemplate('/admin/tpl/popup_auth.php', array(), '');
            unset($_SESSION['_SITE_']['is_form_auth']);
        }
        if ($_SESSION['_SITE_']['is_form_auth'] == 1)
        {
            print $this->ApplyTemplate('/admin/tpl/popup_auth.php', array(), '');
            unset($_SESSION['_SITE_']['is_form_auth']);
        }
        if (isset($_SESSION['_SITE_']['add_new_block']))
          {
            print "<script>$(document).ready(function(){ $('div.admin-div-icon-action[tm=\"{$_SESSION['_SITE_']['add_new_block']['id']}\"]').click(); });</script>";
            unset($_SESSION['_SITE_']['add_new_block']);
        }
    }

    public function loadAdminBlockAdd()
    {
        if ($this->is_adm==1 && ! $this->is_404)
        {
            print $this->ApplyTemplate('/admin/tpl/block_add_button.php');
        }
    }

    public function extc_getTemplatesList($class)
    {
        $class = strtolower($class);
        $tpls = array();
        if (is_dir(S_ROOT . '/tpl/' . $_SESSION['_SITE_']['theme'] . '/' . $_SESSION['_SITE_']['lang'] . '/' . $class . '/'))
        {
            $handle = opendir(S_ROOT . '/tpl/' . $_SESSION['_SITE_']['theme'] . '/' . $_SESSION['_SITE_']['lang'] . '/' . $class . '/');
            while ($file = readdir($handle))
            {
                if (preg_match('~\.php$~i', $file))
                {
                    $arr = file(S_ROOT . '/tpl/' . $_SESSION['_SITE_']['theme'] . '/' . $_SESSION['_SITE_']['lang'] . '/' . $class . '/' . $file);
                    $t['title'] = trim(preg_replace('~^.*\#(.+)\#.*$~isUm', '$1', $arr[0].$arr[1], 1));
                    if ($t['title']=="")
                        continue;
                    $t['file'] = $class . '/' . $file;
                    $tpls[] = $t;
                }
            }
            closedir($handle);
        }
        return $tpls;
    }

    public function extc_getPagerList($cnt_all, $cnt_p)
    {
		global $APP;
        $pages = ceil($cnt_all/$cnt_p);
		$literal = (preg_match('~\?~',$APP->getCurUrl())) ? '&' : '?';
        $this->ext_page_text = $this->ApplyTemplate('pager_list.php', array('pages'=>$pages, 'cnt_p'=>$cnt_p, 'page'=>$this->page, 'lit'=>$literal), '');
    }

    public function extc_getPagerListAdminAjax($cnt_all, $cnt_p, $page=-1, $func='')
    {
        $pages = ceil($cnt_all/$cnt_p);
        $this->ext_page_text = $this->ApplyTemplate('/admin/tpl/pager_list_admin.php', array('pages'=>$pages, 'cnt_p'=>$cnt_p, 'page'=>($page!=-1?$page:$this->page), 'func'=>$func), '');
    }

    public function extc_setMessage($mess, $class, $cl)
    {
        $_SESSION['_SITE_']['messages'][$cl][$class][md5($mess)] = $mess;
    }

    public function extc_getMessages($class, $cl)
    {
        $messages = $_SESSION['_SITE_']['messages'][$cl][$class];
        unset($_SESSION['_SITE_']['messages'][$cl][$class]);
        return $messages;
    }

    public function extc_sendmail($email, $subj, $text, $from, $html=0)
    {
        if ($html==1)
            $headers = 'Content-Type: text/html; charset="utf-8"' . N;
        else
            $headers = 'Content-Type: text/plain; charset="utf-8"' . N;
        $headers .= 'From:' . $from . N;
        return mail($email, $subj, $text, $headers);
    }

    public function extc_test_add_catalog_fields($fields)
    {
        $is_add = false;
        foreach ($fields as $f)
        {
            $vl = trim($_REQUEST[$f['COLUMN_NAME']]);
            if (preg_match('~\|file~is', $f['COLUMN_COMMENT']))
                $vl = ($_FILES[$f['COLUMN_NAME']]['error']==0 && $_FILES[$f['COLUMN_NAME']]['name']!="" || $f['value']!='') ? 1 : "";
            if ($vl!="" && !$is_add)
                $is_add = true;
            if ($f['is_need'] && $vl=="")
            {
                $is_add = false;
                break;
            }
        }

        return $is_add;
    }

}