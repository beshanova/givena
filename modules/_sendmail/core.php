<?php
if(!defined("S_INC") || S_INC!==true)die();

#-- класс рассылки писем. Управляющий класс.

class _Sendmail extends ExtController
{

    public $sql=null;
    public $tm_id = 'main';
    private $data = array();
    const C_NAME = '_Sendmail';
    const C_TITLE = 'Рассылка';
    const C_DESC = 'Рассылка';
    const C_ISPUB = 0;

    public function __construct()
    {
        $this->sql = new _SendmailSql();
        $this->action = (isset($_REQUEST['admin_action'])) ? $_REQUEST['admin_action'] : '';

        $this->data = $this->sql->getModuleData($this->tm_id);
    }

    #-- Функция возвращает контент
    private function getContent()
    {
        $this->actionPopup();

        $mlist = $this->sql->getMList();
        $musers = $this->sql->getMUsers();

        print $this->ApplyTemplateAdmin('block_sendmail.php', array('data'=>$this->data, 'mlist'=>$mlist, 'musers'=>$musers, 'cl'=>$this->getClassName()), $this->getClassName());
    }

    public function loadPopupAdminBlockEdit()
    {
        return $this->getContent();
    }

    private function actionPopup()
    {
        switch ($this->action)
        {
            case 'save_data':
                if ($_REQUEST['data'])
                $this->sql->saveModuleData($this->tm_id, $_REQUEST['data']);
                headerTo($_SESSION['_SITE_']['back_url']);

            case 'save_sendmail':
                if (trim($_REQUEST['data']['title'])!='' && trim($_REQUEST['data']['text'])!='')
                    $this->sql->saveSendmailData($_REQUEST['data']);
                headerTo($_SESSION['_SITE_']['back_url']);

            case 'save_musers':
                $this->sql->saveSendmailUsers($_REQUEST['emails']);
                headerTo($_SESSION['_SITE_']['back_url']);

            case 'delete_muser':
                $this->sql->deleteSendmailUser($_REQUEST['id']);
                print 'ok';
                exit;

            case 'delete_sendmail':
                $this->sql->deleteSendmail($_REQUEST['id']);
                print 'ok';
                exit;

            case 'copy_sendmail':
                $this->sql->copySendmail($_REQUEST['id']);

                $mlist = $this->sql->getMList();
                $musers = $this->sql->getMUsers();

                print $this->ApplyTemplateAdmin('block_sendmail.php', array('data'=>$this->data, 'mlist'=>$mlist, 'musers'=>$musers, 'cl'=>$this->getClassName()), $this->getClassName());
                exit;

            case 'test_sendmail':
                $sendm = $this->sql->getSendmailById($_REQUEST['id']);

                if ($sendm['sendmail_list_status']==0 && $this->data['sendmail_data_email_test']!="" && $this->data['sendmail_data_email_from']!="" && $this->extc_sendmail($this->data['sendmail_data_email_test'], $sendm['sendmail_list_title'], $sendm['sendmail_list_text'], 'Givena.ru <'.$this->data['sendmail_data_email_from'].'>', 1))
                    print 'ok';
                else
                    print 'err';
                exit;

            case 'send_sendmail':
                $this->sql->saveSendmailBySend($_REQUEST['id'], $this->data);
                $sendm = $this->sql->getSendmailById($_REQUEST['id']);

                $mlist = $this->sql->getMList();
                $musers = $this->sql->getMUsers();

                print $this->ApplyTemplateAdmin('block_sendmail.php', array('data'=>$this->data, 'mlist'=>$mlist, 'musers'=>$musers, 'cl'=>$this->getClassName()), $this->getClassName());
                exit;

            case 'send_sendmail_go':
                set_time_limit(0);
                ignore_user_abort(1);
                $sendm = $this->sql->getSendmailById($_REQUEST['id']);
                $emails = $this->sql->getMUsers();

                foreach ($emails as $e)
                {
                    $this->extc_sendmail($e, $sendm['sendmail_list_title'], $sendm['sendmail_list_text'], 'Givena.ru <'.$this->data['sendmail_data_email_from'].'>', 1);
                    sleep(2);
                }
                $this->sql->saveSendmailByDone($_REQUEST['id']);
                exit;

            default:
        }
    }

    public function t_getStatusText($n)
    {
        switch ($n)
        {
            case 0: $text = 'Черновик'; break;
            case 1: $text = 'Идет рассылка'; break;
            case 2: $text = 'Разослано'; break;
            default: $text = '---';
        }
        return $text;
    }

    private function __clone() {}

    public function __destruct() {$this->sql->disconnect();}

    public function getClassName() { return self::C_NAME; }
    public function getClassTitle() { return self::C_TITLE; }
    public function getClassDesc() { return self::C_DESC; }
    public function getClassIsPub() { return self::C_ISPUB; }

}