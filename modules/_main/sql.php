<?php
if(!defined("S_INC") || S_INC!==true)die();
#-- Класс модуля для работы с БД. Должен наследовать класс DB, для подключения функций работы с запросами к БД.

class MainSql extends DB
{

    function __construct()
    {
        parent::__construct();
    }

    function getTopicsList()
    {
        $sql = 'SELECT * FROM ?_topics WHERE 1=1';
        return $this->fetchAll($sql, array());
    }

    function getTopicDataByUrl($url, $pid=0, $item_id=0)
    {
        $arr[] = $url;
        if ($pid>0)
        {
            $sql = 'SELECT * FROM ?_module_menu, ?_topics WHERE module_menu_url=?s AND module_menu_id=?i AND module_menu_topic_id=topic_id';
            $arr[] = $pid;
        }
        else
            $sql = 'SELECT * FROM ?_module_menu, ?_topics WHERE module_menu_url=?s AND module_menu_topic_id=topic_id';
        $data = $this->query($sql, $arr);

        #-- для элемента каталога создаем страницу в ?_topics, если ее там нет и берем из нее данные
        if ($item_id>0)
        {
            $sql = 'SELECT * FROM ?_topics WHERE topic_pid=?i AND item_id=?i';
            $data_t = $this->query($sql, array($data['topic_id'], $item_id));
            if (empty($data_t))
            {
                $this->mysqlquery('INSERT INTO ?_topics (topic_pid, item_id, topic_date_update) VALUES (?i, ?i, NOW())', array( $data['topic_id'], $item_id ));
                $data_t = $this->query($sql, array($data['topic_id'], $item_id));
            }

            $data_t['topic_id'] = $data['topic_id'];
            $data = array_merge($data, $data_t);
        }

        return $data;
    }

    function getTopicModules($id)
    {
        $met = false;
        $item_id = intval($_REQUEST['id']);
        $mods = array();
        $sql = 'SELECT * FROM ?_topics_modules WHERE topics_module_id_topic=?i ORDER BY topics_module_sortby ASC';
        $res = $this->mysqlquery($sql, array($id));
        while ($r = $this->fetchOne($res))
        {
            $mods[] = $r;
            if (in_array($r['topics_module_class'], array('catalog', 'gallery', 'news', 'faq')) && $item_id>0)
                $met = true;
        }

        if ($met)
            foreach ($mods as $k=>$m)
                if (!in_array($m['topics_module_class'], array('catalog', 'gallery', 'news', 'faq', 'breadcrumps')))
                    unset($mods[$k]);

        return $mods;
    }

    function getModulesList()
    {
        $modules = array();
        $handle = opendir(S_ROOT . '/modules/');
        while ($file = readdir($handle))
        {
            if (preg_match('~^[a-z]+~i', $file))
            {
                eval('$obj = new '.ucfirst($file).'();');
                $m['name'] = $obj->getClassName();//::C_NAME;
                $m['title'] = $obj->getClassTitle();//::C_TITLE;
                $m['desc'] = $obj->getClassDesc();//::C_DESC;
                $m['is_pub'] = $obj->getClassIsPub();//::C_ISPUB;
                $modules[] = $m;
                unset($obj);
            }
        }
        closedir($handle);
        return $modules;
    }

    function saveBlockTopic($cl)
    {
        $mx = $this->getMaxSortByMT();
        $sql = 'INSERT INTO ?_topics_modules SET topics_module_id_topic=?i, topics_module_class=?s, topics_module_sortby=?i, topics_module_date_update=NOW()';
        $this->mysqlquery($sql, array( $_SESSION['_SITE_']['topic'][0]['topic_id'], $cl, ($mx+1) ));

        #-- получаем и возвращаем идентификатор вставленного блока
        return $this->last_insert_id();
    }

    private function getMaxSortByMT()
    {
        $sql = 'SELECT MAX(topics_module_sortby) as MX FROM ?_topics_modules WHERE topics_module_id_topic=?i';
        $r = $this->query($sql, array($_SESSION['_SITE_']['topic'][0]['topic_id']));
        return $r['MX'];
    }

    public function getTopicDataById($id)
    {
        $sql = 'SELECT * FROM ?_topics WHERE topic_id=?i';
        return $this->query($sql, array( $id ));
    }
/*
    function getImage($id)
    {
        $sql = 'SELECT * FROM ?_module_image, ?_topics_modules, ?_module_menu WHERE module_image_topic_modules_id=?i AND topics_module_id=module_image_topic_modules_id AND topics_module_id_topic=module_menu_topic_id';
        $data = $this->query($sql, array($id));
        if (preg_match('~(.+\/)(.+)\.(jpg|jpeg|gif|png|bmp)$~is', $data['module_image_src'], $ar))
        {
            $data['module_image_src_data']['dirname'] = $ar[1];
            $data['module_image_src_data']['name'] = $ar[2] . '.' . $ar[3];
        }
        return $data;
    }

    function saveImageData($id)
    {
        $n = $_REQUEST['image'];

        if (isset($_FILES['file']) && $_FILES['file']['error']==0)
        {
            $file = new Upload();
            $file->u_dfile = $_FILES['file'];
            $f_path = $file->u_loading();

            $sql = 'UPDATE ?_module_image SET module_image_title=?s, module_image_link=?s, module_image_src=?s, module_image_target=?s, module_image_is_popup=?i, module_image_tpl=?s, module_image_date_update=NOW() WHERE module_image_topic_modules_id=?i';
            $this->mysqlquery($sql, array( $n['title'], $n['link'], $f_path, $n['target'], $n['is_popup'], $_REQUEST['tpl'], $id ));
        }
        else
        {
            $sql = 'UPDATE ?_module_image SET module_image_title=?s, module_image_link=?s, module_image_target=?s, module_image_is_popup=?i, module_image_tpl=?s, module_image_date_update=NOW() WHERE module_image_topic_modules_id=?i';
            $this->mysqlquery($sql, array( $n['title'], $n['link'], $n['target'], $n['is_popup'], $_REQUEST['tpl'], $id ));
        }

        $sql = 'UPDATE ?_topics_modules SET topics_module_is_active=?i, topics_module_date_update=NOW() WHERE topics_module_id=?i';
        $this->mysqlquery($sql, array( $_REQUEST['is_active'], $id ));
    }
*/
    public function saveParamsBlock()
    {
        if ($_SESSION['_SITE_']['topic'][0]['topic_pid']>0)
        {
            $q = $this->query('SELECT * FROM ?_topics WHERE topic_pid=?i AND item_id=?i', array( $_SESSION['_SITE_']['topic'][0]['topic_pid'], $_SESSION['_SITE_']['topic'][0]['item_id'] ));
            $t_id = $q['topic_id'];
        }
        else
            $t_id = $_SESSION['_SITE_']['topic'][0]['topic_id'];


        $ins_arr = array( $_REQUEST['meta_title'], $_REQUEST['meta_keywords'], $_REQUEST['meta_desc'], $_REQUEST['page_name'], $_REQUEST['page_desc'] );

        $ins_f = '';
        $file = new Upload();
        $file->u_dfile = $_FILES['page_img'];
        if ($f_path = $file->u_loading())
        {
            $ins_f = '`page_img`=?s, ';
            $ins_arr[] = $f_path;
        }

        $ins_arr[] = $t_id;

        $sql = 'UPDATE ?_topics SET meta_title=?s, meta_keywords=?s, meta_desc=?s, page_name=?s, page_desc=?s, '.$ins_f.' topic_date_update=NOW() WHERE topic_id=?i';
        $this->mysqlquery($sql, $ins_arr);
    }

    public function saveBlockSort($tar, $tm_id)
    {
        $met = false;
        #-- получаем данные о перемещаемом топике
        $sql = 'SELECT * FROM ?_topics_modules WHERE topics_module_id=?i';
        $q_cur = $this->query($sql, array($tm_id));
        if ($tar == 'up')
        {
            $sql = 'SELECT * FROM ?_topics_modules WHERE topics_module_id_topic=?i AND topics_module_sortby<?i ORDER BY topics_module_sortby DESC LIMIT 1';
            $q_tar = $this->query($sql, array($q_cur['topics_module_id_topic'], $q_cur['topics_module_sortby']));
            $met = true;
        }
        elseif ($tar == 'down')
        {
            $sql = 'SELECT * FROM ?_topics_modules WHERE topics_module_id_topic=?i AND topics_module_sortby>?i ORDER BY topics_module_sortby ASC LIMIT 1';
            $q_tar = $this->query($sql, array($q_cur['topics_module_id_topic'], $q_cur['topics_module_sortby']));
            $met = true;
        }

        #-- меняем сортировки местами
        if ($met && !empty($q_cur) && !empty($q_tar))
        {
            $sql = 'UPDATE ?_topics_modules SET topics_module_sortby=?i WHERE topics_module_id=?i';
            $this->mysqlquery($sql, array($q_cur['topics_module_sortby'], $q_tar['topics_module_id']));

            $sql = 'UPDATE ?_topics_modules SET topics_module_sortby=?i WHERE topics_module_id=?i';
            $this->mysqlquery($sql, array($q_tar['topics_module_sortby'], $q_cur['topics_module_id']));
        }
    }

    public function main_saveAdminAction($type, $block, $action, $comment='')
    {
        $sql = 'INSERT INTO ?_ustat_admin SET ustat_admin_type=?s, ustat_admin_action=?s, ustat_admin_user_login=?s, ustat_admin_user_id=?i, ustat_admin_block=?s, ustat_admin_url=?s, ustat_admin_comment=?s, ustat_admin_date_update=NOW()';
        $this->mysqlquery($sql, array($type, $action, $_SESSION['_SITE_']['userdata']['user_login'], $_SESSION['_SITE_']['userdata']['user_id'], $block, $_SESSION['_SITE_']['back_url'], $comment));
    }

    public function main_getHelpText($class)
    {
        $sql = 'SELECT * FROM ?_help WHERE cl LIKE ?s';
        $q = $this->query($sql, array(strtolower($class)));
        return $q['txt'];
    }

	public function main_c_saveEmailIntoSendmail($email, $name)
	{
		$email = strtolower(trim($email));
		#-- проверяем емайл ли пытаемся сохранить
		if (preg_match('~.+@.+\..+~i', $email))
		{
			#-- ищем в БД нет ли такого емайла
			$sql = 'SELECT * FROM ?_sendmail_users WHERE sendmail_user_email=?s';
			$q = $this->query($sql, array($email));
			if (empty($q))
			{
				$sql = 'INSERT INTO ?_sendmail_users SET sendmail_user_email=?s, sendmail_user_name=?s, sendmail_user_date_add=NOW()';
				$this->mysqlquery($sql, array($email, $name));
			}
		}
	}

  public function deletePageImg($topic_id)
  {
      if ($topic_id>0)
      {
          $sql = 'UPDATE ?_topics SET page_img="" WHERE topic_id=?i';
          $this->mysqlquery($sql, array($topic_id));
      }
  }

}
?>