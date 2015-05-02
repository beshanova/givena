<?php
if(!defined("S_INC") || S_INC!==true)die();
#-- Класс модуля для работы с БД. Должен наследовать класс DB, для подключения функций работы с запросами к БД.

class UserSql extends DB
{

    function __construct()
    {
        parent::__construct();
    }

    public function getUserDataByLogin($login)
    {
        $sql = 'SELECT * FROM ?_users WHERE LOWER(user_login)=?s';
        return $this->query($sql, array(strtolower(trim($login))));
    }

    public function getUserDataByUserId($uid)
    {
        $sql = 'SELECT * FROM ?_users WHERE user_id=?i';
        return $this->query($sql, array($uid));
    }

    public function getUsersList()
    {
        $sql = 'SELECT * FROM ?_users WHERE 1=1 ORDER BY user_login';
        return $this->fetchAll($sql, array());
    }

    public function saveNewPwd($uid, $pwd)
    {
        $sql = 'UPDATE ?_users SET user_pwd=?s WHERE user_id=?i';
        $this->mysqlquery($sql, array($pwd, $uid));
    }

    public function deleteUserById($uid)
    {
        $user = $this->getUserDataByUserId($uid);
        $sql = 'DELETE FROM ?_users WHERE user_id=?i';
        $this->mysqlquery($sql, array($uid));
        return $user;
    }

    public function updateUser($uid, $user)
    {
        if ($user['g']>0)
        {
            $sql = 'UPDATE ?_users SET user_group=?i, user_login=?s, user_active=?i, user_date_update=NOW() WHERE user_id=?i';
            $this->mysqlquery($sql, array($user['g'], $user['l'], $user['a'], $uid));
        }
        else
        {
            $sql = 'UPDATE ?_users SET user_login=?s, user_active=?i, user_date_update=NOW() WHERE user_id=?i';
            $this->mysqlquery($sql, array($user['l'], $user['a'], $uid));
        }
    }

    public function insertUser($user)
    {
        $sql = 'INSERT INTO ?_users SET user_group=?i, user_login=?s, user_pwd=?s, user_active=?i, user_date_update=NOW()';
        $this->mysqlquery($sql, array($user['g'], $user['l'], $user['p'], $user['a']));
        return $this->last_insert_id();
    }

    public function getAdminStatList($dt1, $dt2)
    {
        $sql = 'SELECT * FROM ?_ustat_admin LEFT JOIN ?_users ON ustat_admin_user_id=user_id WHERE ustat_admin_date_update>="'.$dt1.' 00:00:00" AND ustat_admin_date_update<="'.$dt2.' 23:23:59" ORDER BY ustat_admin_date_update DESC';
        return $this->fetchAll($sql, array());
    }

}
?>