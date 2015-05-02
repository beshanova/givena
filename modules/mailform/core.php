<?php
if(!defined("S_INC") || S_INC!==true)die();

#-- Класс работы с новостями

class Mailform extends ExtController
{

    protected $sql=null;
    public $tm_id=0;
    public $item_id=0;
    public $action='';
    public $func='';
    private $saction='';
    protected $app=null;
    const C_NAME = 'Mailform';
    const C_TITLE = 'Форма связи';
    const C_DESC = 'Форма связи';
    const C_ISPUB = 1;

    public function __construct()
    {
        global $APP;
        $this->app = $APP;
        $this->sql = new MailformSql();
        $this->action = $_REQUEST['admin_action'];
        $this->saction =  $_REQUEST['site_action'];
        if (isset($_REQUEST['p']))
            $this->page = intval($_REQUEST['p']);
    }

    #-- Функция возвращает контент
    private function getContent()
    {
        $data = $this->sql->getModuleData($this->tm_id);
        $fields = $this->sql->getDopFieldsTable($_REQUEST);

        if ($this->saction=='send')
        {
            foreach ($fields as $f)
            {
                if ($f['is_need'] && trim($f['value'])=="")
                {
                    $this->extc_setMessage('Не все обязательные поля заполнены!', 'error', $this->getClassName());
                    headerTo($_SESSION['_SITE_']['back_url']);
                }
            }

            #-- сохраняем заполненные пользователем поля в БД
            $fields = $this->sql->saveMessageForm($this->tm_id, $fields);

            #-- отправляем сообщение администратору
            if ($data['module_mailform_email']!='')
            {
                $text = '';
                foreach ($fields as $f)
                {
                    if ($f['f_type']=='file')
                        $text .= $f['title'] . ': http://' . S_HOST . $f['value'] . N;
                    elseif ($f['f_type']=='checkbox')
                        $text .= $f['title'] . ': '. ($f['value']?'Да':'Нет') . N;
                    else
                        $text .= $f['title'] . ': ' . $f['value'] . N;
                }

                $this->extc_sendmail($data['module_mailform_email'], $data['module_mailform_subject'], $text, 'Givena.ru <'.$data['module_mailform_email'].'>');
				        $this->app->main_c_saveEmailIntoSendmail($_REQUEST['f_email'], $_REQUEST['f_fio']);
            }

            $this->extc_setMessage('Ваше сообщение отправлено!', 'success', $this->getClassName());

            headerTo($_SESSION['_SITE_']['back_url']);
        }

        return $this->ApplyTemplate($data['module_mailform_tpl'], array('fields'=>$fields, 'mes_err'=>$this->extc_getMessages('error', $this->getClassName()), 'mes_ok'=>$this->extc_getMessages('success', $this->getClassName()), 'is_active'=>$data['topics_module_is_active']), $this->getClassName()) ;
    }

    public function __toString()
    {
        return $this->getContent();
    }

    public function loadPopupAdminBlockEdit()
    {
        $this->actionPopup();
        $fields = $this->sql->getDopFieldsTable();
        $data = $this->sql->getModuleData($this->tm_id);
        $tpls = $this->extc_getTemplatesList($this->getClassName());
        $mess = $this->sql->getModuleItems($this->tm_id);

        print $this->ApplyTemplateAdmin('edit.php', array('form'=>$data, 'fields'=>$fields, 'mess'=>$mess, 'tpls'=>$tpls, 'cl'=>$this->getClassName(), 'tm_id'=>$this->tm_id), $this->getClassName());
    }

    private function actionPopup()
    {
        switch ($this->action)
        {
            case 'delete':
                $this->sql->deleteContentBlock($this->tm_id);
                $this->app->main_с_saveAdminAction('delete', self::C_TITLE.' (ID:'.$this->tm_id.')', $this->action, 'Удаление блока');
                headerTo($_SESSION['_SITE_']['back_url']);
            case 'save':
                $this->sql->saveModuleData($this->tm_id);
                $this->app->main_с_saveAdminAction('update', self::C_TITLE.' (ID:'.$this->tm_id.')', $this->action, 'Изменение настроек блока');
                headerTo($_SESSION['_SITE_']['back_url']);
            default:
        }
    }

    function saveNewBlock()
    {
        $this->sql->saveNewBlock($this->tm_id);
        $this->app->main_с_saveAdminAction('insert', self::C_TITLE.' (ID:'.$this->tm_id.')', $this->action, 'Добавление блока');
    }

    private function __clone() {}

    public function __destruct() {}

    public function getClassName() { return self::C_NAME; }
    public function getClassTitle() { return self::C_TITLE; }
    public function getClassDesc() { return self::C_DESC; }
    public function getClassIsPub() { return self::C_ISPUB; }

}