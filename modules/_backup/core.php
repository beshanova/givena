<?php
if(!defined("S_INC") || S_INC!==true)die();

#-- класс резервного копирования. Управляющий класс.

class _Backup extends ExtController
{

    public $sql=null;
    const C_NAME = '_Backup';
    const C_TITLE = 'Резервное копирование';
    const C_DESC = 'Резервное копирование';
    const C_ISPUB = 0;

    public function __construct()
    {
        $this->sql = new _BackupSql();
        $this->action = (isset($_REQUEST['admin_action'])) ? $_REQUEST['admin_action'] : '';
    }

    #-- Функция возвращает контент
    private function getContent()
    {
        $this->actionPopup();

        $files = array();
        $handle = opendir(S_ROOT . '/files/backup/');
        while ($file = readdir($handle))
        {
            if (preg_match('~\.zip$~i', $file))
            {
                $files[] = $file;
            }
        }
        closedir($handle);

        print $this->ApplyTemplateAdmin('block_backup.php', array('files'=>$files, 'cl'=>$this->getClassName()), $this->getClassName());
    }

    public function loadPopupAdminBlockEdit()
    {
        return $this->getContent();
    }

    private function actionPopup()
    {
        switch ($this->action)
        {
            case 'start_backup':
                $types0 = explode(':', trim($_REQUEST['typeb'], ':'));
                if (in_array('f_file', $types0))
                    $types['f_file'] = 1;
                if (in_array('db_db', $types0))
                    $types['db_db'] = 1;

                if ($types['f_file'] || $types['db_db'])
                {
                    global $this_script_dir;
                    $this_script_dir = S_ROOT.'/files/backup';

                    $arc_n = ($types['f_file']?'files_':'') . ($types['db_db']?'db_':'') . date('Ymd_Hi');
                    $this->compress(S_ROOT.'/', $arc_n, $types);
                    chmod( $this_script_dir.'/'.$arc_n.'.zip', 0775 );
                    print('Архивация завершена!');
                }
                exit;

            case 'delete_backup':
                $fname = urldecode($_REQUEST['fname']);
                if (unlink(S_ROOT . '/files/backup/' . $fname))
                    print 'ok';
                else
                    print 'error!';
                exit;

            default:
        }
    }

    private function compress($path, $archivename, $types)
    {
        global $this_script_dir, $zip;
        @chdir(S_ROOT);
        set_time_limit(0);

        $f2del = '';
        if ($types['db_db'])
        {
            if ($types['f_file'])
            {
                $this->sql->BaseDump($path . 'sql_dump.sql');
                $f2del = $path . 'sql_dump.sql';
            }
            else
            {
                $this->sql->BaseDump($this_script_dir.'/'.$archivename.'.sql');
                $f2del = $this_script_dir.'/'.$archivename.'.sql';
            }
        }

        $zip = new ZipArchive();
        $zip->open($this_script_dir.'/'.$archivename.'.zip', ZIPARCHIVE::CREATE);
        if ($types['f_file'])
            $this->_get_dir_tree(S_ROOT.'/');
        else
            $zip->addFile($this_script_dir.'/'.$archivename.'.sql',  $archivename.'.sql');
        $zip->close();

        if ($f2del)
            @unlink($f2del);
    }

    private function _get_dir_tree($parentdir='/')
    {
        global $zip;

        foreach (glob('{,.}*', GLOB_BRACE)?glob('{,.}*', GLOB_BRACE):array() as $file)
        {
            if ($file=='.' || $file=='..') {continue;}
            if (substr($file, -3, 3) == 'zip') {continue;}
            if (in_array($file,array('backup')) && is_dir($file)) {continue;}
            if (is_file($file) && is_readable($file))
            {
                if (!$zip->addFile($file,  str_replace(S_ROOT.'/', '', $parentdir) . $file))
                    continue;
            }
            else if (is_dir($file))
            {
                if (!@chdir ($file)) {continue;}

                $this->_get_dir_tree($parentdir.$file.'/');
                chdir("..");
            }
        }
    }

    private function __clone() {}

    public function __destruct() {$this->sql->disconnect();}

    public function getClassName() { return self::C_NAME; }
    public function getClassTitle() { return self::C_TITLE; }
    public function getClassDesc() { return self::C_DESC; }
    public function getClassIsPub() { return self::C_ISPUB; }

}