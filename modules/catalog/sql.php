<?php
if(!defined("S_INC") || S_INC!==true)die();
#-- Класс модуля для работы с БД. Должен наследовать класс DB, для подключения функций работы с запросами к БД.

class CatalogSql extends DB
{
    var $cur_item_url;

    function __construct()
    {
        parent::__construct();
    }

    function getModuleItemsCondRecomend($cnt)
    {
        $datas = array();
        $sql = 'SELECT I.* FROM ?_module_c_catalog_items I, ?_module_cz_relations_items R WHERE I.module_list_item_is_active=1 AND I.f_checkbox=1 AND I.module_list_item_id=R.module_rel_item ORDER BY RAND() LIMIT ?i';
        $res = $this->mysqlquery($sql, array($cnt));
        while ($r = $this->fetchOne($res))
        {
            foreach ($r as $k=>$d)
                if (preg_match('~(.+\/)(.+)\.(jpg|jpeg|gif|png|bmp)$~is', $d, $ar))
                {
                    $r[$k.'_data']['dirname'] = $ar[1];
                    $r[$k.'_data']['name'] = $ar[2] . '.' . $ar[3];
                }
                elseif (preg_match('~^\d{4}\-\d{2}\-\d{2}~', $d, $ar))
                    $r[$k] = strtotime($d);
            $prices = array();
            $prices[] = ($r['f_p_act'] && $r['f_p_see']) ? $r['f_price'] : '';
            $prices[] = ($r['f_p2_act'] && $r['f_p2_see']) ? $r['f_price2'] : '';
            $prices[] = ($r['f_p3_act'] && $r['f_p3_see']) ? $r['f_price3'] : '';
            $prices[] = ($r['f_p4_act'] && $r['f_p4_see']) ? $r['f_price4'] : '';
            $r['price_mass'] = array_filter($prices, "filter_price_mass");
            //$r['price_mass'] = array_filter(array($r['f_price'],$r['f_price2'],$r['f_price3'],$r['f_price4']));

            $m = $this->query('SELECT M.*, M.module_menu_parent_id as menu_parent FROM ?_module_cz_relations_items R, ?_topics_modules TM, ?_module_menu M WHERE R.module_rel_module_id=TM.topics_module_id AND R.module_rel_item=?i AND TM.topics_module_id_topic=M.module_menu_topic_id', array($r['module_list_item_id']));
            $this->cur_item_url = array();
            $this->getUrlByItemId($m);
            $this->cur_item_url[] = $m['module_menu_url'];
            $r['item_url'] = '/' . implode('/', $this->cur_item_url) . '/';

            $datas[] = $r;
        }

        return $datas;
    }

    public function getLastSeenItemsList($sess, $cnt)
    {
        $items = array();
        $sql = 'SELECT * FROM ?_module_profile_catalog_seen WHERE module_profile_seen_session=?s ORDER BY module_profile_catalog_seen_date_update DESC LIMIT ?i';
        $res = $this->mysqlquery($sql, array($sess, $cnt));
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

            $m = $this->query('SELECT M.*, M.module_menu_parent_id as menu_parent FROM ?_module_cz_relations_items R, ?_topics_modules TM, ?_module_menu M WHERE R.module_rel_module_id=TM.topics_module_id AND R.module_rel_item=?i AND TM.topics_module_id_topic=M.module_menu_topic_id', array($r['module_profile_catalog_item_id']));
            $this->cur_item_url = array();
            $this->getUrlByItemId($m);
            $this->cur_item_url[] = $m['module_menu_url'];
            $data['item_url'] = '/' . implode('/', $this->cur_item_url) . '/';

            $items[] = $data;
        }
        return $items;
    }

    private function getUrlByItemId($m)
    {
        $sql = 'SELECT module_menu_id, module_menu_parent_id as menu_parent, module_menu_title, module_menu_url FROM ?_module_menu WHERE module_menu_id=?i';
        $q = $this->query($sql, array($m['menu_parent']));

        if ($q['menu_parent']>0)
            $this->getUrlByItemId($q);
        $this->cur_item_url[] = $q['module_menu_url'];
    }

}
?>