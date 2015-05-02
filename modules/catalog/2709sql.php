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
            $r['price_mass'] = array_filter(array($r['f_price'],$r['f_price2'],$r['f_price3'],$r['f_price4']));
            $datas[] = $r;
        }

        return $datas;
    }

}
?>