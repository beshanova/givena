<?php
if(!defined("S_INC") || S_INC!==true)die();
#-- Класс модуля для работы с БД. Должен наследовать класс DB, для подключения функций работы с запросами к БД.

class RequestSql extends DB
{

    function __construct()
    {
        parent::__construct();
    }

    function getModuleData($id)
    {
        $sql = 'SELECT * FROM ?_module_mailform, ?_topics_modules, ?_module_menu WHERE module_mailform_topic_modules_id=?i AND topics_module_id=module_mailform_topic_modules_id AND topics_module_id_topic=module_menu_topic_id';
        return $this->query($sql, array($id));
    }

    function saveModuleData($id)
    {
        if (trim($_REQUEST['tpl'])=="")
            $_REQUEST['tpl'] = 'template.php';
        $sql = 'UPDATE ?_module_mailform SET module_mailform_email=?s, module_mailform_subject=?s, module_mailform_tpl=?s, module_mailform_date_update=NOW() WHERE module_mailform_topic_modules_id=?i';
        $this->mysqlquery($sql, array( $_REQUEST['email'], $_REQUEST['subject'], $_REQUEST['tpl'], $id ));

        $sql = 'UPDATE ?_topics_modules SET topics_module_is_active=?i, topics_module_date_update=NOW() WHERE topics_module_id=?i';
        $this->mysqlquery($sql, array( $_REQUEST['is_active'], $id ));
    }

    function saveNewBlock($id)
    {
        if (trim($_REQUEST['tpl'])=="")
            $_REQUEST['tpl'] = 'template.php';
        $sql = 'INSERT INTO ?_module_mailform SET module_mailform_topic_modules_id=?i, module_mailform_email=?s, module_mailform_tpl=?s, module_mailform_date_update=NOW()';
        $this->mysqlquery($sql, array( $id, $_REQUEST['email'], $_REQUEST['tpl'] ));
    }

    function deleteContentBlock($id)
    {
        $sql = 'DELETE FROM ?_module_mailform WHERE module_mailform_topic_modules_id=?i';
        $this->mysqlquery($sql, array( $id ));

        $sql = 'DELETE FROM ?_topics_modules WHERE topics_module_id=?i';
        $this->mysqlquery($sql, array( $id ));
    }

    function saveMessageForm($id, $fields)
    {
        $ins_arr = array( $id );

        $ins_sql = ',';
        foreach ($fields as $k=>$f)
        {
            #-- если заполненное поле является файлом
            if ($f['f_type']=='file')
            {
                $file = new Upload();
                $file->u_dfile = $_FILES[$f['COLUMN_NAME']];
                if ($f_path = $file->u_loading())
                {
                    $ins_sql .= ' `' . $f['COLUMN_NAME'] . '`=?' . $f['dev'] . ',';
                    $ins_arr[] = $f_path;
                    $fields[$k]['value'] = $f_path;
                }
            }
            else
            {
                $ins_sql .= ' `' . $f['COLUMN_NAME'] . '`=?' . $f['dev'] . ',';
                $ins_arr[] = $f['value'];
            }
        }
        $ins_sql = substr($ins_sql, 0, -1);

        $sql = 'INSERT INTO ?_module_mailform_items SET module_mailform_topic_modules_id=?i, module_mailform_item_date_update=NOW()' . $ins_sql;
        $this->mysqlquery($sql, $ins_arr);

        return $fields;
    }

    public function getModuleItems($id)
    {
        $sql = 'SELECT * FROM ?_module_mailform_items WHERE module_mailform_topic_modules_id=?i ORDER BY module_mailform_item_id DESC';
        return $this->fetchAll($sql, array($id));
    }

    #-- функции для работы с дополнительными полями
    public function getDopFieldsTable($data = array())
    {
        $fields = array();
        $sql = 'SELECT COLUMN_NAME, COLUMN_TYPE, COLUMN_COMMENT FROM information_schema.columns WHERE TABLE_NAME="?_module_mailform_items" AND TABLE_SCHEMA=?s AND COLUMN_NAME LIKE "f_%"';
        $res = $this->mysqlquery($sql, array($this->db_getDbName()));
        while ($r = $this->fetchOne($res))
        {
            $r['value'] = (isset($data[$r['COLUMN_NAME']]) ? $data[$r['COLUMN_NAME']] : '');
            $r = array_merge($r, $this->getFieldType($r));
            $fields[$r['COLUMN_NAME']] = $r;
        }
        return $fields;
    }

    private function getFieldType($r)
    {
        $data = array();

        #-- вид поля в админке
        if (preg_match('~^([^\|.]+)\|([^\|.]+)$~isU', $r['COLUMN_COMMENT'], $ar))
        {
            $title = trim($ar[1]);
            $type = trim($ar[2]);
        }
        $name = $r['COLUMN_NAME'];
        $value = trim($r['value']);

        $data['title'] = $title;
        $data['f_type'] = $type;
        switch ($type)
        {
            case 'text':
                $data['field'] = '<input type="text" id="f-' . $name . '" class="admin-field-style-text" name="' . $name . '" value="' . htmlspecialchars($value) . '" />';
                $data['dev'] = 's';
                break;

            case 'textarea':
                $data['field'] = '<textarea id="f-' . $name . '" class="admin-field-style-textarea" name="' . $name . '">' . $value . '</textarea>';
                $data['dev'] = 's';
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
                }
                break;

            case 'checkbox':
                $data['field'] = '<input type="checkbox" id="f-' . $name . '" class="admin-field-style-checkbox" name="' . $name . '" value="1"' . ($value ? ' checked' : '') . '/> - Да/Нет';
                $data['dev'] = 'i';
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
                }
                break;

            case 'file':
                $data['field'] = '<input type="file" id="f-' . $name . '" class="admin-field-style-file" name="' . $name . '" value="' . htmlspecialchars($value) . '" />';
                $data['dev'] = 's';
                break;

            default:
                $data['field'] = '<i>Тип поля задан неверно!</i>';
        }
        return $data;
    }

}
?>