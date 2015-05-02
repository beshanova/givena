<?php
if(!defined("S_INC") || S_INC!==true)die();
#-- Класс модуля для работы с БД. Должен наследовать класс DB, для подключения функций работы с запросами к БД.

class _SendmailSql extends DB
{

    function __construct()
    {
        parent::__construct();
    }

    function getModuleData($id)
    {
        $sql = 'SELECT * FROM ?_sendmail_data WHERE sendmail_data_type=?s';
        return $this->query($sql, array($id));
    }

    function saveModuleData($id, $data)
    {
        $data['email_test'] = trim($data['email_test']);
        if ($data['email_test']!="" && !preg_match('~.+\@.+\.[a-z0-9рф]{2,10}~i', $data['email_test']))
            $data['email_test'] = "";

        $data['email_from'] = trim($data['email_from']);
        if ($data['email_from']!="" && !preg_match('~.+\@.+\.[a-z0-9рф]{2,10}~i', $data['email_from']))
            $data['email_from'] = "";

        $sql = 'UPDATE ?_sendmail_data SET sendmail_data_email_test=?s, sendmail_data_email_from=?s, sendmail_data_date_update=NOW() WHERE sendmail_data_type=?s';
        $this->mysqlquery($sql, array($data['email_test'], $data['email_from'], $id));
    }

    function saveSendmailBySend($id, $data)
    {
        $sql = 'UPDATE ?_sendmail_list SET sendmail_list_email_from=?s, sendmail_list_status=1 WHERE sendmail_list_id=?i';
        $this->mysqlquery($sql, array($data['sendmail_data_email_from'], $id));
    }

    function saveSendmailByDone($id)
    {
        $sql = 'UPDATE ?_sendmail_list SET sendmail_list_status=2, sendmail_list_date_send=NOW() WHERE sendmail_list_id=?i';
        $this->mysqlquery($sql, array($id));
    }

    function getMList()
    {
        $sql = 'SELECT * FROM ?_sendmail_list ORDER BY sendmail_list_date_add DESC';
        return $this->fetchAll($sql, array());
    }

    function getMUsers()
    {
        $sql = 'SELECT * FROM ?_sendmail_users ORDER BY sendmail_user_email ASC';
        return $this->fetchAll($sql, array());
    }

    function saveSendmailData($data)
    {
        $sql = 'INSERT INTO ?_sendmail_list (sendmail_list_title, sendmail_list_text, sendmail_list_date_add) VALUES (?s, ?s, NOW())';
        $this->mysqlquery($sql, array($data['title'], $data['text']));
    }

    function saveSendmailUsers($data)
    {
        $musers = explode("\n", $data);
        foreach ($musers as $u)
        {
            $d = explode("::", $u);
            $email = trim($d[0]);
            $fio = isset($d[1]) ? trim($d[1]) : '';
            if (preg_match('~.+\@.+\.[a-z0-9рф]{2,10}~i', $email))
            {
                $sql = 'INSERT INTO ?_sendmail_users (sendmail_user_name, sendmail_user_email, sendmail_user_date_add) VALUES (?s, ?s, NOW()) ON DUPLICATE KEY UPDATE sendmail_user_name=?s, sendmail_user_email=?s';
                $this->mysqlquery($sql, array($fio, $email, $fio, $email));
            }
        }
    }

    function getSendmailById($id)
    {
        $sql = 'SELECT * FROM ?_sendmail_list WHERE sendmail_list_id=?i';
        return $this->query($sql, array($id));
    }

    function copySendmail($id)
    {
        $data = $this->getSendmailById($id);

        $sql = 'INSERT INTO ?_sendmail_list (sendmail_list_title, sendmail_list_text, sendmail_list_date_add) VALUES (?s, ?s, NOW())';
        $this->mysqlquery($sql, array($data['sendmail_list_title'], $data['sendmail_list_text']));
    }

    function deleteSendmailUser($id)
    {
        $sql = 'DELETE FROM ?_sendmail_users WHERE sendmail_user_id=?i';
        $this->mysqlquery($sql, array($id));
    }

    function deleteSendmail($id)
    {
        $sql = 'DELETE FROM ?_sendmail_list WHERE sendmail_list_id=?i';
        $this->mysqlquery($sql, array($id));
    }

}
?>