<?php
if(!defined("S_INC") || S_INC!==true)die();
#-- Класс модуля для работы с БД. Должен наследовать класс DB, для подключения функций работы с запросами к БД.

class MenuSql extends DB
{

    public $cur_menu;

    function __construct()
    {
        parent::__construct();
        $this->cur_menu = array();
    }

    function getMenuItems($type)
    {

        if ($_SESSION['_SITE_']['is_adm'] && $_REQUEST['ajax']==1)
            $sql = 'SELECT * FROM ?_module_menu WHERE module_menu_type=?s ORDER BY module_menu_level ASC, module_menu_sortby ASC';
        else
            $sql = 'SELECT * FROM ?_module_menu WHERE module_menu_type=?s AND module_menu_is_hidden=0 ORDER BY module_menu_level ASC, module_menu_sortby ASC';
        $res = $this->mysqlquery($sql, array($type));
        $cnt_all = $this->numRowsResult($res);

        if ($cnt_all>0)
        {
            global $APP;
            $level = array();
            while ($r = $this->fetchOne($res))
            {
                if ($r['module_menu_parent_id']>0 && !isset($this->cur_menu[$r['module_menu_parent_id']]))
                  continue;

                $r['class'] = ($_SESSION['_SITE_']['is_adm'] && $r['module_menu_is_hidden']==1) ? 'menu-item-no-active' : '';
                $this->cur_menu[$r['module_menu_id']] = $r;

                $this->cur_menu[$r['module_menu_id']]['current'] = ($APP->topic[0]['module_menu_topic_id'] == $r['module_menu_topic_id']) ? 1 : 0;

                $this->cur_menu[$r['module_menu_id']]['urls'] = (isset($this->cur_menu[$r['module_menu_parent_id']]) && $r['module_menu_url_type']==0 && $this->cur_menu[$r['module_menu_parent_id']]['urls'] ? $this->cur_menu[$r['module_menu_parent_id']]['urls'].'/' : '') . $this->cur_menu[$r['module_menu_id']]['module_menu_url'];

                $this->cur_menu[$r['module_menu_id']]['sortby'] = (isset($this->cur_menu[$r['module_menu_parent_id']]) && $this->cur_menu[$r['module_menu_parent_id']]['sortby'] ? $this->cur_menu[$r['module_menu_parent_id']]['sortby'].'.' : '') . $this->cur_menu[$r['module_menu_id']]['module_menu_sortby'];

                $this->cur_menu[$r['module_menu_id']]['level'] = $r['module_menu_level'];
                if ($r['module_menu_parent_id']>0)
                    $this->cur_menu[$r['module_menu_parent_id']]['is_childs'] = 1;

                if (isset($prev) && $this->cur_menu[$r['module_menu_id']]['level']>$prev['level'] && $prev['level']==0)
                    $this->cur_menu[$prev['module_menu_id']]['is_last_level'] = 1;
                if (++$k==$cnt_all && $this->cur_menu[$r['module_menu_id']]['level']==0)
                    $this->cur_menu[$r['module_menu_id']]['is_last_level'] = 1;

                $prev = $this->cur_menu[$r['module_menu_id']];
            }
        }

        foreach ($this->cur_menu as $k=>$m)
            $this->cur_menu[$k]['sortby'] = 'sort_'.$m['sortby'];
        //printarray($this->cur_menu);
        uasort($this->cur_menu, 'cmp_my_sort');

        return ($this->cur_menu);
    }

    function GetStructureCategors($pid, $topic)
    {
        if ($_SESSION['_SITE_']['is_adm'] && $_REQUEST['ajax']==1)
            $sql = 'SELECT * FROM ?_module_menu WHERE module_menu_parent_id=?s AND module_menu_type=?s ORDER BY module_menu_level ASC, module_menu_sortby ASC';
        else
            $sql = 'SELECT M.*, MM.module_menu_url as module_menu_parent_url FROM ?_module_menu as M LEFT JOIN ?_module_menu as MM ON M.module_menu_parent_id=MM.module_menu_id WHERE M.module_menu_parent_id=?s AND M.module_menu_type=?s AND M.module_menu_is_hidden=0  ORDER BY M.module_menu_level ASC, M.module_menu_sortby ASC';
        $res = $this->mysqlquery($sql, array($pid, $topic));

        return $res;
    }

    function ShowCategors($topic, $pid, $level)
    {
        // генерация дерева каталогов с вложенностями
        global $smarty;

        $result = array();
        $res = $this->GetStructureCategors(intval($pid), $topic);

        $cnt_all = $this->numRowsResult($res);

        if ($cnt_all>0)
        {
            global $APP;
            while($r = $this->fetchOne($res))
            {
                //
                /*if ($r['module_menu_parent_id']>0 && !isset($result[$r['module_menu_parent_id']]))
                  continue;*/

                $r['class'] = ($_SESSION['_SITE_']['is_adm'] && $r['module_menu_is_hidden']==1) ? 'menu-item-no-active' : '';
                $result[$r['module_menu_id']] = $r;

                $result[$r['module_menu_id']]['current'] = ($APP->topic[0]['module_menu_topic_id'] == $r['module_menu_topic_id']) ? 1 : 0;

                $result[$r['module_menu_id']]['urls'] = (isset($result[$r['module_menu_parent_id']]) && $r['module_menu_url_type']==0 && $result[$r['module_menu_parent_id']]['urls'] ? $result[$r['module_menu_parent_id']]['urls'].'/' : '') . $result[$r['module_menu_id']]['module_menu_url'];

                $result[$r['module_menu_id']]['sortby'] = (isset($result[$r['module_menu_parent_id']]) && $result[$r['module_menu_parent_id']]['sortby'] ? $result[$r['module_menu_parent_id']]['sortby'].'.' : '') . $result[$r['module_menu_id']]['module_menu_sortby'];

                $result[$r['module_menu_id']]['level'] = $r['module_menu_level'];

                /*if ($r['module_menu_parent_id']>0)
                    $result[$r['module_menu_parent_id']]['is_childs'] = 1;*/

                if (isset($prev) && $result[$r['module_menu_id']]['level']>$prev['level'] && $prev['level']==0)
                    $result[$prev['module_menu_id']]['is_last_level'] = 1;
                if (++$k==$cnt_all && $result[$r['module_menu_id']]['level']==0)
                    $result[$r['module_menu_id']]['is_last_level'] = 1;

                $prev = $result[$r['module_menu_id']];

                $result[$r['module_menu_id']]['sub']= $this->ShowCategors('top', $r['module_menu_id'], $level+1);
            }
        }
        return $result;

    }
    function GetMenuCatalog($topic='top', $pid=3, $level=1)
    {
        $this->cur_menu = $this->ShowCategors($topic, $pid, $level);
        return $this->cur_menu;
    }

    function GetRightNum($topics_module_id_topic){
        $sql = 'SELECT topics_module_id FROM ?_topics_modules WHERE topics_module_id_topic=?s AND topics_module_class="catalog" LIMIT 1';
        $r = $this->query($sql, array($topics_module_id_topic));
        return $r['topics_module_id'];
    }

    function GetRightNumMenu($module_menu_id){
        $sql = 'SELECT module_menu_topic_id FROM ?_module_menu WHERE module_menu_id=?s LIMIT 1';
        $r = $this->query($sql, array($module_menu_id));
        return $r['module_menu_topic_id'];
    }

    function isExtLink($link)
    {
        return preg_match('~^https?\:\/\/~', trim($link));
    }

    function saveMenuItems($type)
    {
        foreach ($_REQUEST['menu'] as $id=>$m)
        {
            if (!is_numeric($id))
              continue;

            $m['id'] = $id;

            #-- находим топик данного меню и смотрим нет ли еще пунктов меню с данным topic_id
            $sql = 'SELECT * FROM ?_module_menu WHERE module_menu_id=?i';
            $q = $this->query($sql, array($id));
            #-- если это новый пункт меню, добавляем его
            if (empty($q) && intval($m['delete'])!=1)
            {
                $m = $this->checkUrl($m);
                if ($m && trim($m['title'])!='')
                {
                    if ($m['url_type']==0 || $m['url_type']==1)
                        $t_id = $this->isExistTopicItemMenu($m['url']);
                    else
                        $t_id = 0;
                    $sql = 'INSERT INTO ?_module_menu SET module_menu_type=?s, module_menu_parent_id=?i, module_menu_level=?i, module_menu_title=?s, module_menu_url=?s, module_menu_url_type=?i, module_menu_is_hidden=?i, module_menu_topic_id=?i, module_menu_sortby=?i, module_menu_date_update=NOW()';
                    $this->mysqlquery($sql, array( $type, $m['parent_id'], $m['level'], strip_tags($m['title']), $m['url'], $m['url_type'], $m['hide'], $t_id, $m['sort'] ));

                    #-- + ко всему сохраняем поле title для текущего топика
                    $sql = 'UPDATE ?_topics SET meta_title=?s WHERE topic_id=?i';
                    $this->mysqlquery($sql, array($m['title'], $t_id));

                    #-- + ко всему добавляем на вновь созданную страницу блок breadcrumps
                    $sql = 'INSERT INTO ?_topics_modules SET topics_module_id_topic=?i, topics_module_class="breadcrumps", topics_module_sortby=1, topics_module_is_active=1, topics_module_date_update=NOW()';
                    $this->mysqlquery($sql, array($t_id));
                    $mtid = $this->last_insert_id();
                    $sql = 'INSERT INTO ?_module_breadcrumps SET module_breadcrumps_topic_modules_id=?i, module_breadcrumps_date_update=NOW()';
                    $this->mysqlquery($sql, array($mtid));

                    #-- + ко всему добавляем на вновь созданную страницу блок title для текущего топика
                    $sql = 'INSERT INTO ?_topics_modules SET topics_module_id_topic=?i, topics_module_class="title", topics_module_sortby=2, topics_module_is_active=1, topics_module_date_update=NOW()';
                    $this->mysqlquery($sql, array($t_id));
                    $mtid = $this->last_insert_id();
                    $sql = 'INSERT INTO ?_module_title SET module_title_topic_modules_id=?i, module_title_text=?s, module_title_tpl="title/title_h1.php", module_title_date_update=NOW()';
                    $this->mysqlquery($sql, array($mtid, $m['title']));
                }
            }
            #-- если выбрана галочка удаления
            elseif ($q['module_menu_id']>0 && intval($m['delete'])==1)
            {
                $sql = 'SELECT * FROM ?_module_menu WHERE module_menu_topic_id=?i';
                $res = $this->mysqlquery($sql, array( $q['module_menu_topic_id'] ));

                #-- если таких нет, то удаляем топик и привязанные к нему блоки-модули
                if ($this->numRowsResult($res)==1 && $q['module_menu_topic_id']>0)
                {
                    $sql = 'DELETE FROM ?_topics WHERE topic_id=?i';
                    $this->mysqlquery($sql, array($q['module_menu_topic_id']));

                    $sql = 'DELETE FROM ?_topics_modules WHERE topics_module_id_topic=?i';
                    $this->mysqlquery($sql, array($q['module_menu_topic_id']));
                }

                #-- удаляем пункт меню
                $sql = 'DELETE FROM ?_module_menu WHERE module_menu_id=?i';
                $this->mysqlquery($sql, array($id));
            }
            elseif ($q['module_menu_id']>0)
            {
                $m = $this->checkUrl($m);
                if ($m && trim($m['title'])!='')
                {
                    $sql = 'UPDATE ?_module_menu SET module_menu_parent_id=?i, module_menu_level=?i, module_menu_title=?s, module_menu_topic_id=?i, module_menu_url=?s, module_menu_url_type=?i, module_menu_is_hidden=?i, module_menu_sortby=?i, module_menu_date_update=NOW() WHERE module_menu_type=?s AND module_menu_id=?i';
                    $this->mysqlquery($sql, array($m['parent_id'], $m['level'], strip_tags($m['title']), $m['t_id'], $m['url'], $m['url_type'], $m['hide'], $m['sort'], $type, $id));
                }
            }
        }
    }

    private function isExistTopicItemMenu($url)
    {
        $sql = 'SELECT * FROM ?_module_menu WHERE module_menu_url=?s AND module_menu_url_type=0';
        $r = $this->query($sql, array( $url ));
        #-- если существующий топик не найден, создаем новый
        if (empty($r))
        {
            $sql = 'INSERT INTO ?_topics SET topic_date_update=NOW()';
            $this->mysqlquery($sql);
            $r['module_menu_topic_id'] = $this->last_insert_id();
        }
        return $r['module_menu_topic_id'];
    }

    private function isExistItemMenu($type, $url, $pid=0)
    {
        $sql = 'SELECT * FROM ?_module_menu WHERE  module_menu_type=?s AND module_menu_url=?s AND module_menu_parent_id=?i';
        $r = $this->query($sql, array( $type, $url, $pid ));
        #-- если данный пункт меню на данном уровне с такой же ссылкой уже есть, то не даем создавать
        return ($r['module_menu_topic_id']>0 ? true : false);
    }

    private function checkUrl($menu)
    {
        $sql = 'SELECT * FROM ?_module_menu WHERE module_menu_id=?i';
        $m_old = $this->query($sql, array($menu['id']));

        if ($menu['url']=="" && $menu['parent_id']>0)
        {
            $menu['url'] = translit($menu['title']);
        }

        if (isset($menu['url_parent_hidden']) && trim($menu['url_parent_hidden'])!='')
            $menu['url'] = str_replace(trim($menu['url_parent_hidden']), '', $menu['url']);
        $menu['url'] = trim(trim(trim(strtolower($menu['url'])),'/'));

        $menu['url'] = translit($menu['url']);

        $parts_u = explode('/', $menu['url']);
        $cnt_p_u = sizeof($parts_u);

        #-- проверяем внешнюю ссылку
        if (preg_match('~^http\:\/\/~i', $menu['url']) || preg_match('~.+\.[a-z]{2,5}~i', $menu['url']))
        {
            $menu['t_id'] = 0;
            $menu['url_type'] = 2;
            if (!preg_match('~^http\:\/\/~i', $menu['url']))
                $menu['url'] = 'http://' . $menu['url'];
        }
        #-- проверяем ссылку
        elseif (!preg_match('~^\/'.$menu['url_parent_hidden'].'\/?$~i', $menu['url']) || $menu['url_parent_hidden']=='')
        {
            $pid = 0;
            foreach ($parts_u as $u)
            {
                $sql = 'SELECT * FROM ?_module_menu WHERE module_menu_parent_id=?i AND module_menu_url=?s AND module_menu_id<>?i AND module_menu_url_type=0';
                $q = $this->query($sql, array($pid, $u, $menu['id']));
                $pid = $q['module_menu_id'];
            }
            if ($q['module_menu_id']>0)
            {
                $menu['url_type'] = 1;
                $menu['t_id'] = $q['module_menu_topic_id'];
            }
            else
                $menu['t_id'] = $m_old['module_menu_topic_id'];
        }

        #-- значит это обычная ссылка (на раздел)
        if (!isset($menu['url_type']))
        {
            #-- если пункт меню был "ссылкой" и стал разделом
            if ($m_old['module_menu_url_type']>0 && $t_id = $this->isExistTopicItemMenu($menu['url']))
                $menu['t_id'] = $t_id;
            else
                $menu['t_id'] = $m_old['module_menu_topic_id'];
            $menu['url_type'] = 0;
        }

        #-- если очищенный урл содержит более 1 слова и является обычным разделом, то значит что-то не так, и скорее всего ссылка битая - не даем сохранять
        if ( $cnt_p_u>1 && $menu['url_type']==0)
            $menu = false;

        return $menu;
    }

    private function getMaxSortByType($type, $pid=0)
    {
        $sql = 'SELECT MAX(module_menu_sortby) as MX FROM ?_module_menu WHERE module_menu_type=?s AND module_menu_parent_id=?i';
        $r = $this->query($sql, array($type, $pid));
        return $r['MX'];
    }

    public function getMaxIdMenu()
    {
        $sql = 'SELECT MAX(module_menu_id) as MX FROM ?_module_menu WHERE 1=1';
        $r = $this->query($sql, array());
        return $r['MX'];
    }

}
?>