<?php
if(!defined("S_INC") || S_INC!==true)die();
#-- Класс модуля для работы с БД. Должен наследовать класс DB, для подключения функций работы с запросами к БД.

class _ListSql extends DB
{

    private $cl = '';

    function __construct($cl='')
    {
        parent::__construct();
        $this->cl = $cl;
    }

    function getModuleData($id)
    {
        $sql = 'SELECT * FROM ?_module_cz_list, ?_topics_modules, ?_module_menu WHERE module_list_topic_modules_id=?i AND topics_module_id=module_list_topic_modules_id AND topics_module_id_topic=module_menu_topic_id';
        return $this->query($sql, array($id));
    }

    function getModuleCountItems($id)
    {
		if ($_SESSION['_SITE_']['is_adm']==1)
			$sql = 'SELECT COUNT(*) as CNT FROM ?_module_cz_relations_items R, ?_module_c_'.($this->cl).'_items I WHERE R.module_rel_item=I.module_list_item_id AND R.module_rel_module_id=?i';
		else
			$sql = 'SELECT COUNT(*) as CNT FROM ?_module_cz_relations_items R, ?_module_c_'.($this->cl).'_items I WHERE R.module_rel_item=I.module_list_item_id AND R.module_rel_module_id=?i AND module_list_item_is_active=1';
        $q = $this->query($sql, array( $id ));
        return $q['CNT'];
    }

    function getModuleItems($id, $cnt=10, $page=1)
    {
        $datas = array();
        if ($_SESSION['_SITE_']['is_adm']==1)
            $sql = 'SELECT I.* FROM ?_module_cz_relations_items R, ?_module_c_'.($this->cl).'_items I WHERE R.module_rel_module_id=?i AND R.module_rel_item=I.module_list_item_id ORDER BY I.f_title, R.module_rel_sortby LIMIT ?i, ?i';
        else
            $sql = 'SELECT I.* FROM ?_module_cz_relations_items R, ?_module_c_'.($this->cl).'_items I WHERE R.module_rel_module_id=?i AND R.module_rel_item=I.module_list_item_id AND module_list_item_is_active=1 ORDER BY I.f_title, R.module_rel_sortby LIMIT ?i, ?i';

        $res = $this->mysqlquery($sql, array( $id, ($page-1)*$cnt, $cnt ));
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
            $datas[] = $r;
        }
        return $datas;
    }
    
    function getModuleItemsNews($id, $cnt=10, $page=1)
    {
        $datas = array();
        $sql = 'SELECT I.* FROM ?_module_cz_relations_items R, ?_module_c_'.($this->cl).'_items I WHERE R.module_rel_module_id=?i AND R.module_rel_item=I.module_list_item_id ORDER BY I.f_date DESC LIMIT ?i, ?i';
        $res = $this->mysqlquery($sql, array( $id, ($page-1)*$cnt, $cnt ));
        while ($r = $this->fetchOne($res))
        {
            foreach ($r as $k=>$d) {
                if ($k=='f_file' && $d!=""){
                    if (preg_match('~(.+\/)(.+)\.(jpg|jpeg|gif|png|bmp)$~is', $d, $ar))
                    {
                        $r[$k.'_data']['dirname'] = $ar[1];
                        $r[$k.'_data']['name'] = $ar[2] . '.' . $ar[3];
                    }
                }
                if($k=='f_date'){
                    if ($d!=0)
                        if (preg_match('~^\d{4}\-\d{2}\-\d{2}~', $d, $ar))
                            $r[$k] = strtotime($d);
                                     
                }
			};
			$datas[] = $r;
            
        }
        return $datas;
    }

	// main
/* 	function getModuleItemsCondCatalog($id, $cnt)
	{
        $datas = array();
        $sql = 'SELECT I.* FROM ?_module_cz_relations_items R, ?_module_c_'.($this->cl).'_items I WHERE R.module_rel_module_id=?i AND R.module_rel_item=I.module_list_item_id AND f_checkbox=1 ORDER BY R.module_rel_sortby LIMIT ?i, ?i';

        $res = $this->mysqlquery($sql, array( $id, ($page-1)*$cnt, $cnt ));
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
            $datas[] = $r;
        }
        return $datas;
    } */

    function getCatalogItem($id)
    {
        $sql = 'SELECT * FROM ?_module_c_'.($this->cl).'_items WHERE module_list_item_id=?i';
        $data = $this->query($sql, array($id));
        foreach ($data as $k=>$d)
            if (preg_match('~(.+\/)(.+)\.(jpg|jpeg|gif|png|bmp)$~is', $d, $ar))
            {
                $data[$k.'_data']['dirname'] = $ar[1];
                $data[$k.'_data']['name'] = $ar[2] . '.' . $ar[3];
            }
            elseif (preg_match('~^\d{4}\-\d{2}\-\d{2}~', $d, $ar))
                $data[$k] = strtotime($d);
        return $data;
    }

    function getGroupsList($cl, $item_id)
    {
        $sql = 'SELECT *, IF (R.module_rel_module_id>0, 1, 0) as selected
          FROM ?_module_menu MM, ?_topics_modules TM
          LEFT JOIN ?_module_cz_relations_items R ON R.module_rel_module_id=TM.topics_module_id AND R.module_rel_item=?i
          WHERE TM.topics_module_class=?s AND TM.topics_module_id_topic=MM.module_menu_topic_id
          ORDER BY module_menu_title ASC';
        return $this->fetchAll($sql, array($item_id, strtolower($cl)) );
    }

    function saveCatalogData($id)
    {
        $n = $_REQUEST['catalog'];

        global $APP;
        #-- добавляем элемент, если хоть 1 поле заполнено
        $fields = $this->getDopFieldsTable();
        $is_add = $APP->extc_test_add_catalog_fields($fields);
        if ($is_add)
        {
            $ins_arr = array( $n['is_active'] );
            $ins_sql = '';

            foreach ($fields as $f)
            {
                #-- если заполненное поле является файлом
                if ($f['COLUMN_NAME']=='f_price')
                {
                    $ins_sql .= ', `' . $f['COLUMN_NAME'] . '`=?' . $f['dev'];
                    $ins_arr[] = round(str_replace(',','.',$_REQUEST[$f['COLUMN_NAME']]),2);
                }
                elseif (preg_match('~\|file~is', $f['COLUMN_COMMENT']))
                {
                    $file = new Upload();
                    $file->u_dfile = $_FILES[$f['COLUMN_NAME']];
                    if ($f_path = $file->u_loading())
                    {
                        $ins_sql .= ', `' . $f['COLUMN_NAME'] . '`=?' . $f['dev'];
                        $ins_arr[] = $f_path;
                    }
                }
                else
                {
                    $ins_sql .= ', `' . $f['COLUMN_NAME'] . '`=?' . $f['dev'];
                    $ins_arr[] = $_REQUEST[$f['COLUMN_NAME']];
                }
            }

            $sql = 'INSERT INTO ?_module_c_'.($this->cl).'_items SET module_list_item_is_active=?i, module_list_item_date_update=NOW()' . $ins_sql;
            $this->mysqlquery($sql, $ins_arr);
            $item_id = $this->last_insert_id();

            $sql = 'INSERT INTO ?_topics (topic_pid, item_id, meta_title, topic_date_update) VALUES (?i, ?i, ?s, NOW())';
            $this->mysqlquery($sql, array( $_SESSION['_SITE_']['topic'][0]['topic_id'], $item_id, $_REQUEST['f_title'] ));

            #-- сохраняем разделы текущего товара
            foreach ($_REQUEST['group_h'] as $tm)
            {
                if ($_REQUEST['group'][$tm]==1)
                {
                    $mx = $this->catalog_getMaxSortByMT($tm);
                    $sql = 'INSERT INTO ?_module_cz_relations_items (module_rel_module_id, module_rel_item, module_rel_sortby) VALUES (?i, ?i, ?i) ON DUPLICATE KEY UPDATE module_rel_sortby=module_rel_sortby';
                    $this->mysqlquery($sql, array($_REQUEST['tm'], $item_id, $mx+10));
                }
            }
        }

        $cnt = ($_REQUEST['count']>0) ? $_REQUEST['count'] : 10;

        $sql = 'UPDATE ?_module_cz_list SET module_list_count=?i, module_list_list_tpl=?s, module_list_detail_tpl=?s, module_list_date_update=NOW() WHERE module_list_topic_modules_id=?i';
        $this->mysqlquery($sql, array( $cnt, $_REQUEST['template_list'], $_REQUEST['template_detail'], $id ));

        $sql = 'UPDATE ?_topics_modules SET topics_module_is_active=?i, topics_module_date_update=NOW() WHERE topics_module_id=?i';
        $this->mysqlquery($sql, array( $_REQUEST['is_active'], $id ));
    }

    function saveItemCatalog($id)
    {
        if ($id>0)
        {
            $n = $_REQUEST['catalog'];
            $ins_arr = array( $n['module_list_item_is_active'] );
            $ins_sql = '';

            global $APP;
            #-- добавляем элемент, если хоть 1 поле заполнено
            $fields = $this->getDopFieldsTable( $this->getCatalogItem($id) );
            $is_add = $APP->extc_test_add_catalog_fields($fields);
            if ($is_add)
            {
                foreach ($fields as $f)
                {
                    #-- если заполненное поле является файлом
                    if ($f['COLUMN_NAME']=='f_price')
                    {
                        $ins_sql .= ', `' . $f['COLUMN_NAME'] . '`=?' . $f['dev'];
                        $ins_arr[] = round(str_replace(',','.',$_REQUEST[$f['COLUMN_NAME']]),2);
                    }
                    elseif (preg_match('~\|file~is', $f['COLUMN_COMMENT']))
                    {
                        $file = new Upload();
                        $file->u_dfile = $_FILES[$f['COLUMN_NAME']];
                        if ($f_path = $file->u_loading())
                        {
                            $ins_sql .= ', `' . $f['COLUMN_NAME'] . '`=?' . $f['dev'];
                            $ins_arr[] = $f_path;
                        }
                    }
                    else
                    {
                        $ins_sql .= ', `' . $f['COLUMN_NAME'] . '`=?' . $f['dev'];
                        $ins_arr[] = $_REQUEST[$f['COLUMN_NAME']];
                    }
                }
            }

            $ins_arr['id'] = $id;
            $sql = 'UPDATE ?_module_c_'.($this->cl).'_items SET module_list_item_is_active=?i, module_list_item_date_update=NOW()' . $ins_sql . ' WHERE module_list_item_id=?i';
            $this->mysqlquery($sql, $ins_arr);

            #-- сохраняем разделы текущего товара
            foreach ($_REQUEST['group_h'] as $tm)
            {
                if ($_REQUEST['group'][$tm]==1)
                {
                    $mx = $this->catalog_getMaxSortByMT($tm);
                    $sql = 'INSERT INTO ?_module_cz_relations_items (module_rel_module_id, module_rel_item, module_rel_sortby) VALUES (?i, ?i, ?i) ON DUPLICATE KEY UPDATE module_rel_sortby=module_rel_sortby';
                    $this->mysqlquery($sql, array($tm, $id, $mx+10));
                }
                else
                    $this->mysqlquery('DELETE FROM ?_module_cz_relations_items WHERE module_rel_module_id=?i AND module_rel_item=?i', array($tm, $id));
            }

            #-- сохраняем в топике (meta-title) информацию о заголовке элемента
            $sql = 'UPDATE ?_topics SET meta_title=?s, topic_date_update=NOW() WHERE item_id=?i';
            $this->mysqlquery($sql, array($_REQUEST['f_title'], $id));
        }
    }

    function deleteCatalogItem($id, $tm_id)
    {
        if ($id>0)
        {
            $sql = 'SELECT * FROM ?_module_c_'.($this->cl).'_items WHERE module_list_item_id=?i';
            $q = $this->query($sql, array( $id ));
            foreach ($q as $v)
                if (preg_match('~\/files\/\d+\/\d+\/~i', $v))
                    unlink(S_ROOT . $v);

            $sql = 'DELETE FROM ?_module_c_'.($this->cl).'_items WHERE module_list_item_id=?i';
            $this->mysqlquery($sql, array( $id ));

            $sql = 'DELETE FROM ?_module_cz_relations_items WHERE module_rel_module_id=?i AND module_rel_item=?i';
            $this->mysqlquery($sql, array( $tm_id, $id ));

            #-- удаляем запись о топике для этого товара
            $sql = 'DELETE FROM ?_topics WHERE item_id=?i';
            $this->mysqlquery($sql, array( $id ));
        }
    }

    function saveNewBlock($id)
    {
        $sql = 'INSERT INTO ?_module_cz_list SET module_list_topic_modules_id=?i, module_list_date_update=NOW()';
        $this->mysqlquery($sql, array( $id ));
    }

    function deleteContentBlock($id)
    {
        $sql = 'DELETE FROM ?_module_cz_list WHERE module_list_topic_modules_id=?i';
        $this->mysqlquery($sql, array( $id ));

        $sql = 'DELETE FROM ?_module_c_'.($this->cl).'_items WHERE module_list_item_id IN (SELECT module_rel_item FROM ?_module_cz_relations_items WHERE module_rel_module_id=?i)';
        $this->mysqlquery($sql, array( $id ));

        $sql = 'DELETE FROM ?_module_cz_relations_items WHERE module_rel_module_id=?i';
        $this->mysqlquery($sql, array( $id ));

        $sql = 'DELETE FROM ?_topics_modules WHERE topics_module_id=?i';
        $this->mysqlquery($sql, array( $id ));
    }

    #-- функции для работы с дополнительными полями
    public function getDopFieldsTable($data = array())
    {
        $fields = array();
        $sql = 'SELECT COLUMN_NAME, COLUMN_TYPE, COLUMN_COMMENT FROM information_schema.columns WHERE TABLE_NAME="?_module_c_'.($this->cl).'_items" AND TABLE_SCHEMA=?s AND COLUMN_NAME LIKE "f_%"';
        $res = $this->mysqlquery($sql, array($this->db_getDbName()));
        while ($r = $this->fetchOne($res))
        {
            $r = array_merge( $r, $this->getFieldType( $r, trim($data[$r['COLUMN_NAME']]) ) );
            $fields[$r['COLUMN_NAME']] = $r;
        }
        return $fields;
    }

    private function getFieldType($r, $value="")
    {
        $data = array();

        #-- вид поля в админке
        if (preg_match('~^([^\|.]+)\|([^\|.]+)$~isU', $r['COLUMN_COMMENT'], $ar))
        {
            $title = trim($ar[1]);
            $type = trim($ar[2]);
        }
        $name = $r['COLUMN_NAME'];

        $data['title'] = $title;
        $data['f_type'] = $type;
        $data['is_need'] = (preg_match('~\*~', $title)) ? 1 : 0;
        $class_e = ($data['is_need']>0) ? ' catalog-field-is-need-00 catalog-field-00' : ' catalog-field-00';
        switch ($type)
        {
            case 'text':
                $data['field'] = '<input type="text" id="f-' . $name . '" class="admin-field-style-text' . $class_e . '" name="' . $name . '" value="' . htmlspecialchars($value) . '" />';
                $data['dev'] = 's';
                $data['value'] = $value;
                break;

            case 'textarea':
                $data['field'] = '<textarea id="f-' . $name . '" class="admin-field-style-textarea' . $class_e . '" name="' . $name . '">' . $value . '</textarea>';
                $data['dev'] = 's';
                $data['value'] = $value;
                break;

            case 'wisiwig':
                $data['field'] = '<textarea id="f-' . $name . '" class="admin-field-style-textarea' . $class_e . '" name="' . $name . '">' . $value . '</textarea>';
                $data['dev'] = 's';
                $data['value'] = $value;
                break;

            case 'radio':
                if (preg_match_all("~'(.+)'~isU", $r['COLUMN_TYPE'], $ar))
                {
                    $data['field'] = '';
                    foreach ($ar[1] as $a)
                    {
                        $data['field'][] = '<input type="radio" id="f-' . $name . '" class="admin-field-style-radio" name="' . $name . '" value="' . htmlspecialchars($a) . '"' . ($value==$a ? ' checked' : '') . ' /> - ' . $a;
                    }
                    $data['dev'] = 's';
                    $data['value'] = $value;
                }
                break;

            case 'checkbox':
                $data['field'] = '<input type="checkbox" id="f-' . $name . '" class="admin-field-style-checkbox" name="' . $name . '" value="1"' . ($value ? ' checked' : '') . '/>';
                $data['dev'] = 'i';
                $data['value'] = ($value) ? $value : '';
                break;

            case 'select':
                if (preg_match_all("~'(.+)'~isU", $r['COLUMN_TYPE'], $ar))
                {
                    $data['field'][] = '<select id="f-' . $name . '" class="admin-field-style-select" name="' . $name . '">';
                    $data['field'][] = '<option value="">---</option>';
                    foreach ($ar[1] as $a)
                    {
                        $data['field'][] = '<option value="' . htmlspecialchars($a) . '"' . ($a==$value?' selected':'') . '>' . $a . '</option>';
                    }
                    $data['field'][] = '</select>';
                    $data['dev'] = 's';
                    $data['value'] = $value;
                }
                break;

            case 'dtext':
                $value = ($value>0) ? date('Y-m-d', $value) : '';
                $data['field'] = '<input type="text" id="f-' . $name . '" class="admin-field-style-dtext' . $class_e . '" name="' . $name . '" value="' . $value . '" />';
                $data['dev'] = 'd';
                $data['value'] = $value;
                break;

            case 'dttext':
                $value = ($value>0) ? date('Y-m-d H:i:s', $value) : '';
                $data['field'] = '<input type="text" id="f-' . $name . '" class="admin-field-style-dttext' . $class_e . '" name="' . $name . '" value="' . $value . '" />';
                $data['dev'] = 'd';
                $data['value'] = $value;
                break;

            case 'file':
                $data['field'] = '<input type="file" id="f-' . $name . '" class="admin-field-style-file catalog-field-00' . ($data['is_need'] && !$value?' catalog-field-is-need-00':'') . '" name="' . $name . '" value="' . htmlspecialchars($value) . '" />';
                $data['dev'] = 's';
                $data['value'] = $value;
                break;

            default:
                $data['field'] = '<i>Тип поля задан неверно!</i>';
        }
        return $data;
    }

    private function catalog_getMaxSortByMT($tm_id)
    {
        $sql = 'SELECT MAX(module_rel_sortby) as MX FROM ?_module_cz_relations_items WHERE module_rel_module_id=?i';
        $r = $this->query($sql, array($tm_id));
        return $r['MX'];
    }

    public function saveItemSort($tar, $item_id, $tm_id)
    {
        $met = false;
        #-- получаем данные о перемещаемом элементе
        $sql = 'SELECT * FROM ?_module_cz_relations_items WHERE module_rel_module_id=?i AND module_rel_item=?i';
        $q_cur = $this->query($sql, array($tm_id, $item_id));
        if ($tar == 'up')
        {
            $sql = 'SELECT * FROM ?_module_cz_relations_items WHERE module_rel_module_id=?i AND module_rel_sortby<?i ORDER BY module_rel_sortby DESC LIMIT 1';
            $q_tar = $this->query($sql, array($tm_id, $q_cur['module_rel_sortby']));
            $met = true;
        }
        elseif ($tar == 'down')
        {
            $sql = 'SELECT * FROM ?_module_cz_relations_items WHERE module_rel_module_id=?i AND module_rel_sortby>?i ORDER BY module_rel_sortby ASC LIMIT 1';
            $q_tar = $this->query($sql, array($tm_id, $q_cur['module_rel_sortby']));
            $met = true;
        }

        #-- меняем сортировки местами
        if ($met && !empty($q_cur) && !empty($q_tar))
        {
            $sql = 'UPDATE ?_module_cz_relations_items SET module_rel_sortby=?i WHERE module_rel_module_id=?i AND module_rel_item=?i';
            $this->mysqlquery($sql, array($q_cur['module_rel_sortby'], $tm_id, $q_tar['module_rel_item']));

            $sql = 'UPDATE ?_module_cz_relations_items SET module_rel_sortby=?i WHERE module_rel_module_id=?i AND module_rel_item=?i';
            $this->mysqlquery($sql, array($q_tar['module_rel_sortby'], $tm_id, $q_cur['module_rel_item']));
        }
    }


    #-- функция поиска по модулю удовлетворяющих записей --
    #-- возвращает данные в формате:
    #--  [ключем массива результата является ID модуля] =>
    #--   [content] - найденная строка
    #--   [date_update]    - дата и время последнего изменения найденной информации (записи)
    #--   [date_update_format]    - дата и время последнего изменения найденной информации (записи) в человеко-понятном представлении
    public function searchContentModule($tm_ids, $q_arr)
    {

        $fields = $this->getDopFieldsTable();
        foreach ($fields as $f)
        {
            if (in_array($f['f_type'], array('select','text', 'textarea', 'file', 'checkbox')))
                $selects[] = $f['COLUMN_NAME'];
        }

        if (sizeof($selects)>0)
        {
            $sql_arr = array();
            $sql_inl = '';
            foreach ($q_arr as $a)
            {
                $sql_inl .= ' AND (';
                foreach ($selects as $s)
                {
                    $sql_inl .= '`'.$s.'` LIKE ?s OR';
                    $sql_arr[] = '%'.$a.'%';
                }
                $sql_inl = substr($sql_inl, 0, -3);
                $sql_inl .= ')';
            }

            $tm_arr = implode(',', $tm_ids);
            $sql = 'SELECT '.implode(', ', $selects).',
                module_list_item_id as item_id,
                module_rel_module_id as tm_id,
                UNIX_TIMESTAMP(module_list_item_date_update) as date_update,
                module_list_item_date_update as date_update_format
              FROM ?_module_cz_relations_items, ?_module_c_'.($this->cl).'_items
              WHERE module_rel_module_id IN ('.$tm_arr.') AND module_rel_item=module_list_item_id AND module_list_item_is_active=1'.$sql_inl;
            $res = $this->mysqlquery($sql, $sql_arr);
            while ($r = $this->fetchOne($res))
            {
                foreach ($selects as $s)
                    $r['content'] .= $r[$s] . (preg_match('~\W$~ius', trim($r[$s]))||trim($r[$s])==''? '' : '. ');
                $datas[$r['item_id']] = $r;
                //unset($datas[$r['tm_id']]['tm_id']);
            }
        }
        return $datas;
    }

    public function saveSeenItem($item_id, $sess)
    {
        $sql = 'INSERT INTO ?_module_profile_catalog_seen (module_profile_catalog_item_id, module_profile_seen_session, module_profile_catalog_seen_date_update) VALUES (?i, ?s, NOW()) ON DUPLICATE KEY UPDATE module_profile_catalog_seen_date_update=NOW()';
        $this->mysqlquery($sql, array($item_id, $sess));
    }

    #-- функция получения данных по заказываемому товару --
    #-- возвращает данные в формате:
    #--  [id] - ID-товара
    #--  [pr] - тип цены при котором покупается товар
    public function add2BasketItem($id, $pr=1)
    {
        $datas = array();
        $item = $this->getCatalogItem($id);
        $fields = $this->getDopFieldsTable($item);
        $fields['f_price'] = $fields['f_price' . ($pr>1 ? $pr : '')];

        #-- получаем даннные по категории для товара
        $q = $this->query('SELECT * FROM ?_module_cz_relations_items, ?_topics_modules, ?_module_menu WHERE module_rel_item=?i AND module_rel_module_id=topics_module_id AND topics_module_id_topic=module_menu_topic_id', array($id));
        while ($q['module_menu_parent_id']>0 && $q['module_menu_level']>1)
          $q = $this->query('SELECT * FROM ?_module_menu WHERE module_menu_id=?i', array($q['module_menu_parent_id']));
        $category = $q['module_menu_title'];

        if (!empty($item) && $item['module_list_item_is_active']>0)
        {
            foreach ($fields as $k=>$it)
            {
                if ( $it['f_type'] == 'text' )
                    $datas[$k] = $it['value'];
                elseif ( $it['f_type'] == 'file')
                    $datas[$k.'_data'] = ( isset($item[$k.'_data']) ) ? $item[$k.'_data'] : $item[$k];
            }
            $datas['is_item'] = (preg_match('~Есть~i', $fields['f_availability']['value'])) ? 1 : 0;
            $datas['category'] = $category;
            $datas['cnt'] = 0;
            $koef = 1;
            if ($datas['f_sale']) $koef = 1 - intval($datas['f_sale'])/100;
            $datas['f_price'] = $datas['f_price']*$koef;
        }
        return $datas;
    }

}
?>