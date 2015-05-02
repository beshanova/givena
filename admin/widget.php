<p><a href="/admin/widget.php?t=1">Обновить изображения в разделах модуля "Subgroups"</a></p>
<p><a href="/admin/widget.php?t=2">Добавить на страницы каталога "Хлебные крошки", "Заголовок" (если их нет)</a></p>
<p><a href="/admin/widget.php?t=3">Обновить meta-title на страницах (если они не заданы)</a></p>

<?php
define('S_INC', true);
$S_ROOT = $_SERVER['DOCUMENT_ROOT'];
include('../includes/defines.inc.php');
include('../includes/config.inc.php');
include('../includes/functions.inc.php');
$db = new DB();

$t = intval($_GET['t']);

if ($t==1)
{
  $cnt_img = 0;

  $sql = 'SELECT * FROM ?_topics, ?_topics_modules WHERE (topics_module_class="catalog" OR topics_module_class="subgroups") AND topic_id=topics_module_id_topic AND page_img="" GROUP BY topic_id';
  $res = $db->mysqlquery($sql);
  while ($r = $db->fetchOne($res))
  {
    #-- находим идентификатор меню страницы
    $q = $db->query('SELECT * FROM ?_module_menu WHERE module_menu_topic_id=?i AND module_menu_url_type=0', array($r['topic_id']));

    #-- проверяем у топика наличие подразделов
    $q1 = $db->query('SELECT * FROM ?_topics, ?_topics_modules, ?_module_menu WHERE module_menu_parent_id=?i AND module_menu_topic_id=topic_id AND topics_module_class="catalog" AND topic_id=topics_module_id_topic GROUP BY topic_id ORDER BY RAND() LIMIT 1', array($q['module_menu_id']));

    #-- и если подразделы есть, то нужно найти товар с картинкой в этом подразделе
    if (!empty($q1))
    {
      $r['topics_module_id'] = $q1['topics_module_id'];
    }

    $sql2 = 'SELECT * FROM ?_module_cz_relations_items, ?_module_c_catalog_items WHERE module_rel_module_id=?i AND module_list_item_id=module_rel_item AND f_file<>"" ORDER BY RAND() LIMIT 1';
    $r2 = $db->query($sql2, array($r['topics_module_id']));

    if (is_file($S_ROOT.$r2['f_file']) && file_exists($S_ROOT.$r2['f_file']))
    {
      $sql3 = 'UPDATE ?_topics SET page_img=?s WHERE topic_id=?i';
      $db->mysqlquery($sql3, array($r2['f_file'], $r['topic_id']));
      $cnt_img++;
//      printarray($r2);
    }
  }

  print '<p>Обновлено изображений в разделах: ' . $cnt_img . '</p>';
}
elseif ($t==2)
{
  $cnt_upd_t = $cnt_upd_b = 0;

  $sql = 'SELECT * FROM ?_topics_modules, ?_module_menu WHERE topics_module_id_topic=module_menu_topic_id AND (topics_module_class="catalog" OR topics_module_class="subgroups") AND module_menu_url_type=0 GROUP BY module_menu_topic_id';
  $res = $db->mysqlquery($sql);
  while ($r = $db->fetchOne($res))
  {
    $data = $mods = array();
    $res2 = $db->mysqlquery('SELECT * FROM ?_topics_modules WHERE topics_module_id_topic=?i ORDER BY topics_module_sortby', array($r['module_menu_topic_id']));
    while ($r2 = $db->fetchOne($res2))
    {
      $data[] = $r2;
      $mods[] = $r2['topics_module_class'];
    }

    #-- если нет "хлебных крошек", то добавляем блок на первое место
    if (!in_array('breadcrumps', $mods))
    {
      $db->mysqlquery('UPDATE ?_topics_modules SET topics_module_sortby=topics_module_sortby+1 WHERE topics_module_id_topic=?i AND topics_module_sortby>=1', array($r['module_menu_topic_id']));

      $db->mysqlquery('INSERT INTO ?_topics_modules SET topics_module_id_topic=?i, topics_module_class="breadcrumps", topics_module_sortby=1, topics_module_is_active=1, topics_module_date_update=NOW()', array($r['module_menu_topic_id']));
      $mid = $db->last_insert_id();
      $db->mysqlquery('INSERT INTO ?_module_breadcrumps SET module_breadcrumps_topic_modules_id=?i, module_breadcrumps_date_update=NOW()', array($mid));
      $cnt_upd_b++;
    }

    #-- если нет заголовка, то добавляем его на второе место
    if (!in_array('title', $mods))
    {
      $db->mysqlquery('UPDATE ?_topics_modules SET topics_module_sortby=topics_module_sortby+1 WHERE topics_module_id_topic=?i AND topics_module_sortby>=2', array($r['module_menu_topic_id']));

      $db->mysqlquery('INSERT INTO ?_topics_modules SET topics_module_id_topic=?i, topics_module_class="title", topics_module_sortby=2, topics_module_is_active=1, topics_module_date_update=NOW()', array($r['module_menu_topic_id']));
      $mid = $db->last_insert_id();
      $db->mysqlquery('INSERT INTO ?_module_title SET module_title_topic_modules_id=?i, module_title_text=?s, module_title_tpl="title/title_h1.php", module_title_date_update=NOW()', array($mid, $r['module_menu_title']));
      $cnt_upd_t++;
    }
  }

  print '<p>Добавлено "хлебных крошек" в разделы: ' . $cnt_upd_b . '</p>';
  print '<p>Добавлено "заголовков" в разделы: ' . $cnt_upd_t . '</p>';
}
elseif ($t==3)
{
  $cnt_upd_t = 0;
  $sql = 'SELECT * FROM ?_topics LEFT JOIN ?_module_menu ON module_menu_topic_id=topic_id WHERE meta_title=""';
  $res = $db->mysqlquery($sql);
  while ($r = $db->fetchOne($res))
  {
    if ($r['topic_pid']>0 && $r['item_id']>0)
    {
      $t = $db->query('SELECT * FROM ?_topics WHERE topic_id=?i', array($r['topic_pid']));
      $sql = 'SELECT * FROM ?_topics_modules WHERE topics_module_id_topic=?i';
      $res2 = $db->mysqlquery($sql, array($t['topic_id']));
      while ($r2 = $db->fetchOne($res2))
      {
        if (in_array($r2['topics_module_class'], array('news','catalog')))
        {
          $q = $db->query('SELECT * FROM ?_module_c_'.$r2['topics_module_class'].'_items WHERE module_list_item_id=?i', array($r['item_id']));
          $r['module_menu_title'] = $q['f_title'];
        }
      }
    }

//    printarray($r);
    $title = trim($r['module_menu_title']);
    if ($title!="")
    {
      $db->mysqlquery('UPDATE ?_topics SET meta_title=?s, topic_date_update=NOW() WHERE topic_id=?i', array($title, $r['topic_id']));
      $cnt_upd_t++;
    }
  }

  print '<p>Обновлено "Meta-Title" на страницах: ' . $cnt_upd_t . '</p>';
}

unset($db);
?>