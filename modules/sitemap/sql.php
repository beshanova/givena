<?php
if(!defined("S_INC") || S_INC!==true)die();
#-- Класс модуля для работы с БД. Должен наследовать класс DB, для подключения функций работы с запросами к БД.

class SitemapSql extends DB
{

    function __construct()
    {
        parent::__construct();
    }

    function getModuleData($id)
    {
        $sql = 'SELECT * FROM ?_module_sitemap, ?_topics_modules WHERE module_sitemap_topic_modules_id=?i AND topics_module_id=module_sitemap_topic_modules_id';
        return $this->query($sql, array($id));
    }

    function saveSitemapData($id)
    {
        $sql = 'UPDATE ?_module_sitemap SET module_sitemap_menu_type=?s, module_sitemap_tpl=?s, module_sitemap_date_update=NOW() WHERE module_sitemap_topic_modules_id=?i';
        $this->mysqlquery($sql, array( $_REQUEST['menu_type'], $_REQUEST['template'], $id ));

        $sql = 'UPDATE ?_topics_modules SET topics_module_is_active=?i, topics_module_date_update=NOW() WHERE topics_module_id=?i';
        $this->mysqlquery($sql, array( $_REQUEST['is_active'], $id ));
    }

    function getMenuTypesList()
    {
        $sql = 'SELECT DISTINCT(module_menu_type) as `type` FROM ?_module_menu WHERE 1=1';
        return $this->fetchAll($sql);
    }

    function getMenuItems($type)
    {
        $cur_menu = array();

        $sql = 'SELECT * FROM ?_module_menu WHERE module_menu_type=?s AND module_menu_is_hidden=0 ORDER BY module_menu_level ASC, module_menu_sortby ASC';
        $res = $this->mysqlquery($sql, array($type));
        $cnt_all = $this->numRowsResult($res);

        if ($cnt_all>0)
        {
            global $APP;
            $level = array();
            while ($r = $this->fetchOne($res))
            {
                if ($r['module_menu_parent_id']>0 && !isset($cur_menu[$r['module_menu_parent_id']]))
                  continue;

                $r['class'] = ($_SESSION['_SITE_']['is_adm'] && $r['module_menu_is_hidden']==1) ? 'menu-item-no-active' : '';
                $cur_menu[$r['module_menu_id']] = $r;

                $cur_menu[$r['module_menu_id']]['current'] = ($APP->topic[0]['module_menu_topic_id'] == $r['module_menu_topic_id']) ? 1 : 0;

                $cur_menu[$r['module_menu_id']]['urls'] = (isset($cur_menu[$r['module_menu_parent_id']]) && $r['module_menu_url_type']==0 && $cur_menu[$r['module_menu_parent_id']]['urls'] ? $cur_menu[$r['module_menu_parent_id']]['urls'].'/' : '') . $cur_menu[$r['module_menu_id']]['module_menu_url'];

                $cur_menu[$r['module_menu_id']]['sortby'] = (isset($cur_menu[$r['module_menu_parent_id']]) && $cur_menu[$r['module_menu_parent_id']]['sortby'] ? $cur_menu[$r['module_menu_parent_id']]['sortby'].'.' : '') . $cur_menu[$r['module_menu_id']]['module_menu_sortby'];

                $cur_menu[$r['module_menu_id']]['level'] = $r['module_menu_level'];
                if ($r['module_menu_parent_id']>0)
                    $cur_menu[$r['module_menu_parent_id']]['is_childs'] = 1;

                if (isset($prev) && $cur_menu[$r['module_menu_id']]['level']>$prev['level'] && $prev['level']==0)
                    $cur_menu[$prev['module_menu_id']]['is_last_level'] = 1;
                if (++$k==$cnt_all && $cur_menu[$r['module_menu_id']]['level']==0)
                    $cur_menu[$r['module_menu_id']]['is_last_level'] = 1;

                $prev = $cur_menu[$r['module_menu_id']];
            }
        }

        foreach ($cur_menu as $k=>$m)
            $cur_menu[$k]['sortby'] = 'sort_'.$m['sortby'];
        uasort($cur_menu, 'cmp_my_sort');

        return $cur_menu;
    }

    function saveNewBlock($id)
    {
        if (trim($_REQUEST['tpl'])=="")
            $_REQUEST['tpl'] = 'template.php';
        $sql = 'INSERT INTO ?_module_sitemap SET module_sitemap_topic_modules_id=?i, module_sitemap_tpl=?s, module_sitemap_date_update=NOW()';
        $this->mysqlquery($sql, array( $id, $_REQUEST['tpl'] ));
    }

    function deleteContentBlock($id)
    {
        $sql = 'DELETE FROM ?_module_sitemap WHERE module_sitemap_topic_modules_id=?i';
        $this->mysqlquery($sql, array( $id ));

        $sql = 'DELETE FROM ?_topics_modules WHERE topics_module_id=?i';
        $this->mysqlquery($sql, array( $id ));
    }

}
?>