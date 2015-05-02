<?php
if(!defined("S_INC") || S_INC!==true)die();
#-- Класс модуля для работы с БД. Должен наследовать класс DB, для подключения функций работы с запросами к БД.

class _BackupSql extends DB
{

    function __construct()
    {
        parent::__construct();
    }

    function BaseDump($arc_name)
    {
      $last_row = $start_row;
      $mem = (strlen(ini_get("memory_limit")) > 0) ? intVal(ini_get("memory_limit")) : 1;

      $tables = $this->fetchAll('SHOW TABLES;');
      $ptab = Array();
      foreach ($tables as $row)
      {
        $ar = each($row);
        $table = $ar[1];
        $ptab[] = $table;
      }

      $f = fopen($arc_name, "w");
      $i = 0;
      $dump = "";

      foreach($ptab as $i=>$table)
      {
          if (strlen($table)>0)
          {
              $drop = true; //$stat || !preg_match("~ustat_admin~i",$table); // если не переносим статистику, то только создаём структуру таблицы
              $dump = $this->createTable($table, $drop);
              fwrite($f, $dump."\n");

              $row_count = $this->query("SELECT COUNT(*) as count FROM `$table`");
              if($row_count["count"] > 0)
                  $row_next = $this->getData($ptab[$i], $f, $row_count["count"]);
          }
      }
      fclose($f);

      return true;
    }

    private function createTable($table, $drop = true)
    {
      $sql = "SHOW CREATE TABLE `$table`";
      $row = $this->query($sql);

      $com = "\n\n";
      $com .= "-- --------------------------------------------------------" ."\n";
      $com .= "-- \n";
      $com .= "-- Table structure for table `$table`\n";
      $com .= "-- \n";
      $com .= "\n";

      return $com."\n\n\n".($drop ? "DROP TABLE IF EXISTS `".$table."`;\n".$row["Create Table"] : str_replace('CREATE TABLE','CREATE TABLE IF NOT EXISTS', $row["Create Table"])).';';
    }

    private function getData($table, $file, $row_count)
    {
      $dump = "";
      $step = "";

      $com = "\n-- \n";
      $com .= "-- Dumping data for table  `".$table."`\n";
      $com .= "-- \n";
      $com .= "\n";

      fwrite($file, $com."\n");

      $sql = "SHOW COLUMNS FROM `$table`";
      $res = $this->mysqlquery($sql);
      $num = Array();
      $i = 0;

      //Определяем тип поля
      while($row = $this->fetchOne($res))
      {
        if(preg_match("/^(\w*int|year|float|double|decimal)/", $row["Type"]))
          $meta[$i] = 0;
        elseif(preg_match("/^(\w*binary)/", $row["Type"]))
        {
          $meta[$i] = 1;
        } else
          $meta[$i] = 2;
        $i++;
      }

      $sql = "SHOW TABLE STATUS LIKE '$table'";
      $tbl_info = $this->query($sql);
      $step = 1+round(1048576*0.5 / ($tbl_info["Avg_row_length"] + 1));
      $last_row = 0;

      while($last_row <= ($row_count-1))
      {
        $sql = "SELECT * FROM `$table` LIMIT $last_row, $step";
        $res = $this->mysqlquery($sql);

        while($row = $this->fetchOne($res))
        {
          $i = 0;
          foreach($row as $key => $val)
          {
            if (!isset($val) || is_null($val))
                $row[$key] = 'NULL';
            else
              switch($meta[$i])
              {
                case 0:
                  $row[$key] = $val;
                break;
                case 1:
                  if (empty($val) && $val != '0')
                    $row[$key] = '\'\'';
                  else
                    $row[$key] = '0x' . bin2hex($val);
                break;
                case 2:
                  $row[$key] = "'".mysql_escape_string($val)."'";
                break;
              }
            $i++;
          }
          fwrite($file, "INSERT INTO `$table` VALUES (".implode(",", $row).");\n");
        }
        $last_row += $step;
      }

      if($last_row >= ($row_count-1))
        return -1;
      else
        return $last_row;
    }

}
?>