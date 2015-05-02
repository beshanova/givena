<?php
if(!defined("S_INC") || S_INC!==true)die();
#-- Класс модуля для работы с БД. Должен наследовать класс DB, для подключения функций работы с запросами к БД.

class ImportSql extends DB
{

    function __construct()
    {
        parent::__construct();
    }

    function getFieldsCatalog($data, $table)
    {
        #-- ищем поля каталога таблицы, чтобы не обновлять поля, которых там нет, т.к. это будет вызывать ошибку
        $sql = 'SELECT COLUMN_NAME, COLUMN_TYPE, COLUMN_COMMENT FROM information_schema.columns WHERE TABLE_NAME="?_module_c_'.$table.'_items" AND TABLE_SCHEMA=?s AND COLUMN_NAME LIKE "f_%"';
        $res = $this->mysqlquery($sql, array($this->db_getDbName()));
        while ($r = $this->fetchOne($res))
        {
            $cn = strtolower($r['COLUMN_NAME']);
            if (!isset($data[$cn]))
                unset($data[$cn]);
        }
        return $data;
    }

    function saveCatalogItem($data, $v_main, $table, $rels_n, $groups, $menus)
    {
        $id_ret = 0;

        $sql = 'SELECT * FROM ?_module_c_'.$table.'_items WHERE '.$v_main.'=?s';

        $q = $this->query($sql, array($data[$v_main]));

        $da_sql = array();
        if (!empty($q))
        {
            $ds_sql = '';
            foreach ($data as $k=>$d)
            {
                if (isset($rels_n[$k]))
                {
                    $ds_sql .= ', `'.$k.'`=?s';
                    $da_sql[] = $d;
                }
            }
            $da_sql[] = $data[$v_main];

            $sql = 'UPDATE ?_module_c_'.$table.'_items SET module_list_item_date_update=NOW() '.$ds_sql.' WHERE '.$v_main.'=?s';
            $this->mysqlquery($sql, $da_sql);

            $act = 'update';
            $id_ret = $q['module_list_item_id'];
        }
        else
        {
            $ds_sql1 = $ds_sq2 = '';
            foreach ($data as $k=>$d)
            {
                if (isset($rels_n[$k]))
                {
                    $ds_sql1 .= ', `'.$k.'`';
                    $ds_sql2 .= ', ?s';
                    $da_sql[] = $d;
                }
            }

            $sql = 'INSERT INTO ?_module_c_'.$table.'_items (module_list_item_date_update '.$ds_sql1.') VALUES (NOW() '.$ds_sql2.')';
            $this->mysqlquery($sql, $da_sql);

            $act = 'insert';
            $id_ret = $this->last_insert_id();
        }


        #-- здесь, по переданному параметру $groups будем искать и привязывать товары к разделам
        #-- предполагается, что номер разделов в $groups совпадает с номерами пунктов меню module_menu_id в таблице ?_module_menu

        foreach ($groups as $g)
        {
            $mid = intval(trim($g));
            #-- если номер раздела не определен или этот номер указывает на несуществующий раздел
            if ( ! $mid || ! isset($menus[$mid]) ) continue;

            #-- находим последний индекс сортировки раздела
            $sql = 'SELECT * FROM ?_module_cz_relations_items WHERE module_rel_module_id=?i ORDER BY module_rel_sortby DESC';
            $q = $this->query($sql, array($menus[$mid]));

            #-- если товар новый, просто добавляем его в нужные категории
            if ($act == 'insert')
            {
                $sql = 'INSERT INTO ?_module_cz_relations_items (module_rel_module_id, module_rel_item, module_rel_sortby) VALUES (?i, ?i, ?i)';
                $this->mysqlquery($sql, array($menus[$mid], $id_ret, $q['module_rel_sortby']+10));
            }
            else
            {
                #-- находим товар с этим разделом и если такой связи нет, то добавляем
                $sql = 'SELECT * FROM ?_module_cz_relations_items WHERE module_rel_module_id=?i AND module_rel_item=?i';
                $t = $this->query($sql, array($menus[$mid], $id_ret));

                if (empty($t))
                {
                    $sql = 'INSERT INTO ?_module_cz_relations_items (module_rel_module_id, module_rel_item, module_rel_sortby) VALUES (?i, ?i, ?i)';
                    $this->mysqlquery($sql, array($menus[$mid], $id_ret, $q['module_rel_sortby']+10));
                }
            }
            $gr_new[] = $menus[$mid];
        }

        if (sizeof($gr_new)>0 && $act=='update')
        {
            #-- удаляем все связи товара с разделами, которые не значатся в выгрузке
            $sql = 'DELETE FROM ?_module_cz_relations_items WHERE module_rel_module_id NOT IN ('.implode(',',$gr_new).') AND module_rel_item=?i';
            $this->mysqlquery($sql, array($id_ret));
        }

        return array('id'=>$id_ret, 'act'=>$act);
    }

    function getExistsGroupsList($table)
    {
        $menus = array();
        #-- получаем список всех категорий с каталогами $table
        $sql = 'SELECT * FROM ?_module_menu M, ?_topics_modules T WHERE T.topics_module_class=?s AND T.topics_module_id_topic=M.module_menu_topic_id';
        $res = $this->mysqlquery($sql, array($table));
        while ($r = $this->fetchOne($res))
        {
            $menus[$r['module_menu_id']] = $r['topics_module_id'];
        }
        return $menus;
    }

    function deleteOldItems($table, $exis_id)
    {
        $ids_del = array();

        #-- находим все элементы каталога $table
        $sql = 'SELECT * FROM ?_module_c_'.$table.'_items WHERE 1=1';
        $res = $this->mysqlquery($sql, array());
        while ($r = $this->fetchOne($res))
        {
            if ( ! in_array($r['module_list_item_id'], $exis_id) )
            {
                $ids_del[] = $r['module_list_item_id'];
            }
        }

        if (sizeof($ids_del)>0)
        {
            $sql = 'DELETE FROM ?_module_c_'.$table.'_items WHERE module_list_item_id IN ('.implode(',',$ids_del).')';
            $this->mysqlquery($sql, array());

            $sql = 'DELETE FROM ?_module_cz_relations_items WHERE module_rel_item IN ('.implode(',',$ids_del).')';
            $this->mysqlquery($sql, array());
        }

        return sizeof($ids_del);
    }

}
?>