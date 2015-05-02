<?php
if(!defined("S_INC") || S_INC!==true)die();
#-- основной класс для работы с запросами к БД

class DB
{
    private $ini_file='';
    private $db_sets=array();
    private $prefix='';
    private $lang='';
    protected static $conn = FALSE;

    public function __construct()
    {
        $this->lang=$_SESSION['_SITE_']['lang'];
        $this->connect();
    }

    private function connect()
    {
        global $_CONN, $_PREFIX, $_DBN;
        if ( ! $_CONN )
        {
            $this->ini_file = S_ROOT . '/includes/' . ($_SESSION['_SITE_']['is_loc'] ? 'db_config_local.ini' : 'db_config.ini');
            if(file_exists($this->ini_file))
            {
                $this->db_sets = parse_ini_file($this->ini_file);
                $_PREFIX = $this->prefix = $this->db_sets['DB_PREFIX'] . '_' . $this->lang;

                $_CONN = $this->conn = mysql_connect($this->db_sets['HOST'], $this->db_sets['DB_USER'], $this->db_sets['DB_PASSWORD']) or die("Could not connect:".mysql_error());
                mysql_select_db($this->db_sets['DB_NAME']) or die("Could not select to database!");
                $this->mysqlquery("SET NAMES UTF8");
                $this->mysqlquery("SET CHARACTER SET UTF8");
                $_DBN = $this->db_sets['DB_NAME'];
            }
            else
            {
                die("Файл с настройками БД не найден!");
            }
        }
        else
        {
            $this->conn = $_CONN;
            $this->prefix = $_PREFIX;
        }
    }

    public function mysqlquery($sql, $params=array())
    {
        $sql = $this->parseSql($sql, $params);

        global $dev_mas;
        $dev_mas['sqls'][] = $sql;

        if (isset($_REQUEST['dev']) && $_REQUEST['dev']==1)
            print $sql.'<hr>';

        $r = mysql_query($sql);
        if($_SESSION['_SITE_']['is_loc'])
        {
            if(mysql_error($this->conn))
            {
                print '<hr>';
                print mysql_error($this->conn);
                print N.BR.N.BR.$sql;
                exit;
            }
        }
        return $r;
    }

    public function last_insert_id()
    {
        return mysql_insert_id();
    }

    public function query($sql, $params=array())
    {
        $r = $this->mysqlquery($sql, $params);
      	return $this->fetchOne($r);
    }

    public function fetchOne($res)
    {
        return mysql_fetch_assoc($res);
    }

    public function fetchAll($sql, $params=array())
    {
        $datas = array();
        $res = $this->mysqlquery($sql, $params);
      	while ($r = $this->fetchOne($res))
        {
            $datas[] = $r;
        }
        return $datas;
    }

    public function numRowsResult($res)
    {
        return mysql_num_rows($res);
    }

    private function parseSql($sql, $params)
    {
        $repl = "";
        $sql = str_replace('?_', $this->prefix.'_', $sql);
        $cnt_pars = sizeof($params);
        if ($cnt_pars>0 && substr_count($sql, '?')==$cnt_pars)
        {
            foreach ($params as $v)
            {
                $repl = '<#g8+'.md5(time()).'_>';
                $sql = preg_replace('~\?(d|s|i|f)~', '?#$1', $sql);
                $v = mysql_escape_string(preg_replace('~\?(d|s|i|f)~', '?'.$repl.'$1', $v));
                if (preg_match('~\?\#(d|s|i|f)~', $sql, $arr, 0, 1))
                {
                    switch ($arr[1])
                    {
                        case 'd': $v = '"'.parseDate2Sql($v).'"'; break;
                        case 's': $v = '"'.$this->parseVars($v).'"'; break;
                        case 'i': $v = intval($v); break;
                        case 'f': $v = floatval($v); break;
                    }
                    $sql = preg_replace('~\?\#'.$arr[1].'~', $v, $sql, 1);
                }
            }
        }
        return str_replace($repl, '', $sql);
    }

    private function parseVars($text)
    {
        $search = array('@<script[^>]*?>(.*)?</script>@si',        // find javascript tags
            '@<style[^>]*?>(.*)?</style>@siU',        // style tags properly
            '@<![\s\S]*?--[ \t\n\r]*>@'    // Strip multi-line comments including CDATA
        );
        return preg_replace($search, '$1', $text);
    }

    public function disconnect()
    {
        mysql_close($this->conn);
    }

    public function __destruct() {}

    public function db_getDbName()
    {
        global $_DBN;
        return $_DBN;
    }

}
?>