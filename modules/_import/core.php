<?php
if(!defined("S_INC") || S_INC!==true)die();

#-- самый первый класс, с которого все запускается. Управляющий класс.

class _Import extends ExtController
{

    protected $sql=null;
    public $tm_id=0;
    public $action='';
    protected $app=null;
    const C_NAME = '_Import';
    const C_TITLE = 'Импорт CSV';
    const C_DESC = 'Импорт CSV';
    const C_ISPUB = 0;

    #-- Тип каталога в который будет производится импорт
    protected $type = 'catalog';
    #-- Переменная связей полей каталога и номеров столбцов в csv-файле
    protected $rels = array(
      'f_title'=>2,
      'f_lat'=>3,
      'f_text'=>18,
      'module_list_item_is_active'=>0,
      'f_date'=>'',
      'f_file'=>4,
      'f_file2'=>'',
      'f_file3'=>'',
      'f_file4'=>'',
      'f_file5'=>'',
      'f_file6'=>'',
      'f_author'=>5,
      'f_city'=>6,
      'f_year'=>7,
      'f_group'=>8,
      'f_menu'=>9,
      'f_height'=>10,
      'f_periodbloom'=>11,
      'f_diameter'=>12,
      'f_color'=>13,
      'f_aroma'=>14,
      'f_diseases'=>15,
      'f_rain'=>16,
      'f_frost'=>17,
      'f_price'=>19,
      'f_price2'=>20,
      'f_price3'=>21,
      'f_price4'=>22,
      'f_availability'=>1);
    #-- Название переменной в каталоге, присутствующая в $rels, по которой будет определяться уникальность товара
    protected $v_main = 'f_title';
    #-- что делать с товарами каталога, которых нет в файле импорта: 0-ничего, 1-удалить
    private $v_del = 0;

    public function __construct()
    {
        global $APP;
        $this->app = $APP;
        $this->sql = new ImportSql();
        $this->action = $_REQUEST['admin_action'];
		
		if (isset($_REQUEST['v_del']))
			$this->v_del = intval($_REQUEST['v_del']);
    }

    #-- Функция возвращает контент
    private function getContent()
    {
        return $this->ApplyTemplate('import.php', array(), $this->getClassName());
    }

    public function __toString()
    {
        return $this->getContent();
    }

    public function loadPopupAdminBlockEdit()
    {
        $this->actionPopup();

        print $this->ApplyTemplateAdmin('import.php', array('cl'=>$this->getClassName()), $this->getClassName());
    }

	
	
	
    private function actionPopup()
    {
		//приведение названий файлов в нужному регистру
		$this->sql->updateImagePath();
        $uploaddir = S_ROOT . '/files/';
        switch ($this->action)
        {
            case 'upload':
                $fname = basename($_FILES['csvfile']['name']);
                if (!preg_match('~\.csv$~', $fname))
                    print 'Ошибка! Расширение файла должно быть csv!';
                elseif (move_uploaded_file($_FILES['csvfile']['tmp_name'], $uploaddir . $this->type.'.csv'))
                    print 'ok';
                else
                    print 'Ошибка! Файла не загружен!';
                exit;

            case 'load':
                #-- импорт товаров
                if (file_exists($uploaddir . $this->type.'.csv'))
                {
                    #-- проверяем массив обновляемых полей в таблице и убираем те поля, которых в этой таблице нет
                    $rels_n = $this->sql->getFieldsCatalog($this->rels, $this->type);

                    $rels_r = array_flip($this->rels);
                    #-- массив обновленных, вставленных идентификаторов
                    $idis_in = $idis_up = $exis_id = array();

                    #-- список идентификаторов пунктов меню с ключами идентификаторов блоков
                    $groups0 = $this->sql->getExistsGroupsList($this->type);

                    $txt = file_get_contents($uploaddir . $this->type.'.csv');
                    $lines = explode("\n", $txt);
                    //printarray($lines);exit;
                    $cnt_i = sizeof($lines);
                    $cnt_l = 0;



                    for ($i=0; $i<$cnt_i; $i++)
                    {

                        $line = trim($lines[$i]);

                        #-- устанавливаем сколько строк пропускаем от начала, т.к. это строки заголовков
                        if ($i<1 || ! $line) continue;

                        $cnt_l++;
                        $str_arr = array();
                        $line = $lines[$i];
                        $cels = explode(';', $line);
                        $cnt_j = sizeof($cels);
                        for ($j=0; $j<$cnt_j; $j++)
                        {
                            if (isset($rels_r[$j]))
                                //$str_arr[$rels_r[$j]] = trim($cels[$j]);
                                $str_arr[$rels_r[$j]] = iconv( 'cp1251', 'utf-8', trim($cels[$j]) );
                        }

                        #-- специальная вставка для обработки блока f_menu - принадлежность товара к категории
                        $groups = array();
                        if (isset($str_arr['f_menu']) && $str_arr['f_menu'])
                            $groups = explode(',', $str_arr['f_menu']);
                        #-- переменная $groups содержит идентификаторы пунктов меню, которые мы сравним с массивом $groups0 и если пересечений нет, то значит раздела в системе нет, и значит добавлять товар не нужно, т.к. он добавится в никуда
                        $met = false;


                        //$groups = array('152');

                        foreach ($groups as $g)
                            if (isset($groups0[$g]))
                                $met = true;
                        if (!$met) continue;


                        #-- специальная вставка для обработки блока f_file - картинки к товару
						
                        $files = array();
                        if (isset($str_arr['f_file']) && $str_arr['f_file'])
                            $files = explode(',', $str_arr['f_file']);
                        for ($k=0; $k<6; $k++)
                        {
                            $ind = ($k+1!=1) ? $k+1 : '';
                            $file = trim($files[$k]);
                            if ($file!='' && file_exists(S_ROOT . '/files/_import/' . strtolower($file)))
                            {
                                $str_arr['f_file'.$ind] = '/files/_import/' . $file;
                                $rels_n['f_file'.$ind] = '/files/_import/' . $file;
                            }
                            else
                            {
                                $str_arr['f_file'.$ind] = '';
                                $rels_n['f_file'.$ind] = '';
                            }
                        }

                        #--блок для price, видимость цен
                        if ($str_arr['f_price']!="" || intval($str_arr['f_price'])>0)
                            {
                                $str_arr['f_p_see'] = 1;
                                $rels_n['f_p_see'] = 1;
								$str_arr['f_p_act'] = 1;
                                $rels_n['f_p_act'] = 1;
                            }
                        else {
                            $str_arr['f_p_see']=0;
                            $str_arr['f_p_act']=0;
                            $rels_n['f_p_see']=0;
                            $rels_n['f_p_act']=0;
                        }
                        if ($str_arr['f_price2']!="" || intval($str_arr['f_price2'])>0)
                            {
                                $str_arr['f_p2_see'] = 1;
                                $rels_n['f_p2_see'] = 1;
								$str_arr['f_p2_act'] = 1;
                                $rels_n['f_p2_act'] = 1;
                            }
                        else {
                            $str_arr['f_p2_see']=0;
                            $str_arr['f_p2_act']=0;
                            $rels_n['f_p2_see']=0;
                            $rels_n['f_p2_act']=0;
                        }
                        if ($str_arr['f_price3']!="" || intval($str_arr['f_price3'])>0)
                            {
                                $str_arr['f_p3_see'] = 1;
                                $rels_n['f_p3_see'] = 1;
								$str_arr['f_p3_act'] = 1;
                                $rels_n['f_p3_act'] = 1;
                            }
                        else {
                            $str_arr['f_p3_see']=0;
                            $str_arr['f_p3_act']=0;
                            $rels_n['f_p3_see']=0;
                            $rels_n['f_p3_act']=0;
                        }
                        if ($str_arr['f_price4']!="" || intval($str_arr['f_price4'])>0)
                            {
                                $str_arr['f_p4_see'] = 1;
                                $rels_n['f_p4_see'] = 1;
								$str_arr['f_p4_act'] = 1;
                                $rels_n['f_p4_act'] = 1;
                            }
                        else {
                            $str_arr['f_p4_see']=0;
                            $str_arr['f_p4_act']=0;
                            $rels_n['f_p4_see']=0;
                            $rels_n['f_p4_act']=0;
                        }

                        

                        if ( $str_arr[$this->v_main] != '' && sizeof($groups)>0)
                        {
                            $data = $this->sql->saveCatalogItem($str_arr, $this->v_main, $this->type, $rels_n, $groups, $groups0);
                            if ($data['act']=='update')
                                $idis_up[] = $data['id'];
                            else
                                $idis_in[] = $data['id'];
                            $exis_id[] = $data['id'];

                        }
                    }

                    unlink($uploaddir . $this->type.'.csv');
                    print 'Импорт каталога завершен';
                    print '<br>импортировано записей/всего в файле: ' . ( sizeof($idis_up) + sizeof($idis_in) ) . '/' . $cnt_l;
                    print '<br>из них обновлено: ' . ( sizeof($idis_up) );
                    print '<br>из них добавлено: ' . ( sizeof($idis_in) );

                    #-- если настроено удаление, то удаляем товары, которых не было в файле выгрузки
                    if ($this->v_del)
                    {
                        $cnt_de = $this->sql->deleteOldItems($this->type, $exis_id);
                        print '<br>было удалено: ' . $cnt_de;
                    }
                }
                else
                    print 'Файл импорта не найден на сервере!';

                $this->app->main_с_saveAdminAction('import', self::C_TITLE, $this->action, 'Импорт каталога');
                exit;

            default:
        }
    }

    private function __clone() {}

    public function __destruct() {}

    public function getClassName() { return self::C_NAME; }
    public function getClassTitle() { return self::C_TITLE; }
    public function getClassDesc() { return self::C_DESC; }
    public function getClassIsPub() { return self::C_ISPUB; }

}