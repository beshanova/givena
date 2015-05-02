<?php
if(!defined("S_INC") || S_INC!==true)die();
#-- класс для работы с файлами

class Upload
{
    public $u_dfile = array();
    private $u_name = '';
    private $u_dir = '';
    private $u_ret = false;

    public function __construct()
    {
        $this->u_dir = '/files/' . date('Ymd') . '/' . date('His') . '/';
        $k = 0;
        while (is_dir(S_ROOT . $this->u_dir))
            $this->u_dir = '/files/' . date('Ymd') . '/' . date('His') . '_' . (++$k) . '/';
    }

    public function u_loading()
    {
        if (! $this->u_dfile['error'] && $this->u_dfile['size']>0)
        {
            if ( ! is_dir(S_ROOT . $this->u_dir))
                mkdir(S_ROOT . $this->u_dir, 0777, true);

            $this->u_name = strtolower($this->u_dfile['name']);
            if (preg_match('~[^a-z0-9_\-\.]~i', $this->u_name))
                $this->u_name = translit($this->u_name);

            if ( move_uploaded_file($this->u_dfile['tmp_name'], S_ROOT . $this->u_dir . $this->u_name) )
                $this->u_ret = $this->u_dir . $this->u_name;
        }

        return strtolower($this->u_ret);
    }

    public function __destruct() {}

}
?>