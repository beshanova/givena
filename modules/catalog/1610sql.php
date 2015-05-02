<?php
if(!defined("S_INC") || S_INC!==true)die();
#-- Класс модуля для работы с БД. Должен наследовать класс DB, для подключения функций работы с запросами к БД.

class CatalogSql extends DB
{

    function __construct()
    {
        parent::__construct();
    }

    function getModuleItemsCondRecomend($cnt)
    {
        $datas = array();
        $sql = 'SELECT * FROM ?_module_c_catalog_items WHERE module_list_item_is_active=1 AND f_checkbox=1 ORDER BY RAND() LIMIT ?i';
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

            $items[] = $data;
        }
        return $items;
    }

}
?>