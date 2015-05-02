<?php
if(!defined("S_INC") || S_INC!==true)die();
#-- Класс модуля для работы с БД. Должен наследовать класс DB, для подключения функций работы с запросами к БД.

class Advanced_searchSql extends DB
{

    private $cur_url = array();

    function __construct()
    {
        parent::__construct();
    }

    function getSearchData($type)
    {
        $sql = 'SELECT * FROM ?_module_search WHERE module_search_type=?s';
        return $this->query($sql, array($type));
    }

    function getModuleData($type, $tpl_f, $tpl_r)
    {
        $this->data = $this->getSearchData($type);

        if (empty($this->data))
        {
            $sql = 'INSERT INTO ?_module_search SET module_search_type=?s, module_search_tpl_form=?s, module_search_tpl_results=?s, module_search_date_update=NOW()';
            $this->mysqlquery($sql, array($type, $tpl_f, $tpl_r));

            $this->data = $this->getSearchData($type);
        }
        elseif ( ($tpl_f != "" && $this->data['module_search_tpl_form'] != $tpl_f) || ($tpl_r != "" && $this->data['module_search_tpl_results'] != $tpl_r))
        {
            $sql = 'UPDATE ?_module_search SET module_search_tpl_form=?s, module_search_tpl_results=?s WHERE module_search_type=?s';
            $this->mysqlquery($sql, array($tpl_f, $tpl_r, $type));

            $this->data['module_search_tpl_form'] = $tpl_f;
            $this->data['module_search_tpl_results'] = $tpl_r;
        }
    }

    function saveSearchData($type)
    {
        $sql = 'UPDATE ?_module_search SET module_search_cnt_results=?i, module_search_cnt_literals=?i, module_search_date_update=NOW() WHERE module_search_type=?s';
        $this->mysqlquery($sql, array($_REQUEST['cnt'], $_REQUEST['cnt_lit'], $type));
    }

    function searchTextInModules($q_arr, $min_h, $max_h, $cnt_lit=100)
    {
        $menu = new Menu();
        $str_list_rosy = $menu->f_menu_sub_topic(100);

        $datas = $menu_url = array();
        $sql = 'SELECT
                  module_menu_type as mtype,
                  module_menu_id as menu_id,
                  module_menu_parent_id as menu_parent,
                  module_menu_url as menu_url,
                  UNIX_TIMESTAMP(module_menu_date_update) as menu_date_update,
                  module_menu_date_update as menu_date_update_format,
                  module_menu_topic_id as topic_id,
                  module_menu_title as topic_title,
                  topics_module_class as cl,
                  topics_module_id as module_id,
                  UNIX_TIMESTAMP(topics_module_date_update) as module_date_update,
                  topics_module_date_update as module_date_update_format
                FROM ?_module_menu, ?_topics_modules
                WHERE module_menu_url_type=0 AND module_menu_topic_id=topics_module_id_topic
                    AND topics_module_is_active=1
                    AND module_menu_id IN ('.$str_list_rosy.')
                    AND topics_module_class = "catalog"';
        $menus = $this->fetchAll($sql);

        foreach ($menus as $m)
        {
            $modules[$m['cl']][] = $m['module_id'];
            $menus_t[$m['module_id']] = $m;
        }

        $reg = implode('[0-9а-яa-z]*|',$q_arr) . '[0-9а-яa-z]*';

        foreach ($modules as $cl=>$tm_ids)
        {
            if (!isset($obj[$cl]))
                eval('$obj[\''.$cl.'\'] = new '.ucfirst($cl).'();');

            $result = array();

            /*if (method_exists($obj[$cl], 'searchContentModule') && sizeof($tm_ids)>0)
                $results = $obj[$cl]->searchContentModule($tm_ids, $q_arr);*/

            $results = $this->searchContentModule($tm_ids, $q_arr, $min_h, $max_h);

            if (!empty($results))
            {
                foreach ($results as $rt)
                {
                    $tm_id = $rt['module_rel_module_id'];
                    $this->cur_url = array();
                    /*$rt['content'] = strip_tags($rt['content']);
                    $rt['content'] = preg_replace("~[\s\n\t]+~sm", ' ', trim($rt['content']));
                    $rt['content'] = preg_replace('~('.$reg.')([^0-9^а-я^a-z])~isUum', '<span class="searched-text">$1</span>$2', $rt['content']);
                    $rt['content'] = preg_replace('~(.{0,'.$cnt_lit.'}<span.*\/span>.{0,'.$cnt_lit.'}).*~isum', '<tru>... $1 ...', $rt['content']);
                    $rt['content'] = preg_replace('~.*<tru>~', '', $rt['content']);
*/
                    $dop_url = (isset($rt['module_list_item_id'])&&$rt['module_list_item_id']>0) ? '?id='.$rt['module_list_item_id'] : '';

                    $m = $menus_t[$tm_id];

                    if ($m['menu_url'] == '')
                        $rt['topic_url'] = '/' . $dop_url;
                    elseif ($m['menu_parent'] == 0)
                        $rt['topic_url'] = '/' . $m['menu_url'] . '/' . $dop_url;
                    else
                    {
                        if (!isset($menu_url[$m['menu_id']]))
                        {
                            $this->getUrlByMenuId($m);
                            $this->cur_url[] = $m['menu_url'];
                            $menu_url[$m['menu_id']] = $this->cur_url;
                        }
                        else
                            $this->cur_url = $menu_url[$m['menu_id']];
                        $rt['topic_url'] = '/' . implode('/', $this->cur_url) . '/' . $dop_url;
                    }

                    $datas[] = $rt;
                }
            }
            //printarray($datas);
            //$datas = $results;
        }
        return $datas;
    }

    private function getUrlByMenuId($m)
    {
        $sql = 'SELECT module_menu_id, module_menu_parent_id as menu_parent, module_menu_title, module_menu_url FROM ?_module_menu WHERE module_menu_id=?i';
        $q = $this->query($sql, array($m['menu_parent']));

        if ($q['menu_parent']>0)
            $this->getUrlByMenuId($q);
        $this->cur_url[] = $q['module_menu_url'];
    }

    #-- функция поиска по модулю удовлетворяющих записей --
    #-- возвращает данные в формате:
    #--  [ключем массива результата является ID модуля] =>
    #--   [content] - найденная строка
    #--   [date_update]    - дата и время последнего изменения найденной информации (записи)
    #--   [date_update_format]    - дата и время последнего изменения найденной информации (записи) в человеко-понятном представлении
    public function searchContentModule($tm_ids, $q_arr, $min_h, $max_h)
    {
        $sql_arr = array();
        $sql_inl = '';
        foreach ($q_arr as $a)
        {
            $sql_inl .= ' I.f_color LIKE ?s OR';
            $sql_arr[] = '%'.$a.'%';
        }

        $tm_arr = implode(',', $tm_ids);
        $sql_inl = substr($sql_inl, 0, -3);

        $datas = array();
        $sql = 'SELECT * FROM ?_module_c_catalog_items I, ?_module_cz_relations_items R
                WHERE  R.module_rel_item=I.module_list_item_id
                AND I.module_list_item_is_active=1 AND
                R.module_rel_module_id IN ('.$tm_arr.')
                AND ('.$sql_inl.')
                GROUP BY I.module_list_item_id
                ORDER BY R.module_rel_sortby';
        //echo($sql);
        $res = $this->mysqlquery($sql, $sql_arr);
        while ($r = $this->fetchOne($res))
        {

            foreach ($r as $k=>$d){
                if (preg_match('~(.+\/)(.+)\.(jpg|jpeg|gif|png|bmp)$~is', $d, $ar))
                {
                    $r[$k.'_data']['dirname'] = $ar[1];
                    $r[$k.'_data']['name'] = $ar[2] . '.' . $ar[3];
                }
                elseif (preg_match('~^\d{4}\-\d{2}\-\d{2}~', $d, $ar))
                    $r[$k] = strtotime($d);
            }

            $prices = array();
            $prices[] = ($r['f_p_act'] && $r['f_p_see']) ? $r['f_price'] : '';
            $prices[] = ($r['f_p2_act'] && $r['f_p2_see']) ? $r['f_price2'] : '';
            $prices[] = ($r['f_p3_act'] && $r['f_p3_see']) ? $r['f_price3'] : '';
            $prices[] = ($r['f_p4_act'] && $r['f_p4_see']) ? $r['f_price4'] : '';
            $r['price_mass'] = array_filter($prices, "filter_price_mass");
            //$r['price_mass'] = array_filter(array($r['f_price'],$r['f_price2'],$r['f_price3'],$r['f_price4']));

            if ($min_h || $max_h){
                    $arr_h = explode('-', $r['f_height']);
                    if (count($arr_h)==2){
                        if (max($min_h, intval($arr_h[0])) < min($max_h, intval($arr_h[1])))
                            $datas[] = $r;
                    }
                    elseif (count($arr_h)==1){
                        if (($min_h < intval($arr_h[0])) && ($max_h > intval($arr_h[0])))
                            $datas[] = $r;
                    }
            }
            else
                $datas[] = $r;
        }
        //printarray($datas);
        return $datas;
    }

}
?>