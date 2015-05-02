<table>
  <tr style="font-weight: bold;">
    <td>Дата</td>
    <td>Автор</td>
    <td>Блок</td>
    <td>Действие</td>
    <td>Комментарий</td>
  </tr>
  <? foreach ($stats as $s) : ?>
  <tr>
    <td><?= dateFormat($s['ustat_admin_date_update'],true) ?></td>
    <td><?= ($s['user_login']=='' ? '<del>'.$s['ustat_admin_user_login'].'</del>' : ($s['user_login']!=$s['ustat_admin_user_login']?'<i>'.$s['ustat_admin_user_login'].'</i>->'.$s['user_login']:$s['ustat_admin_user_login'])) ?> (ID:<?= $s['ustat_admin_user_id'] ?>)</td>
    <td><a href="<?= $s['ustat_admin_url'] ?>" target="_blank"><?= $s['ustat_admin_block'] ?></a></td>
    <td><?= $s['ustat_admin_type'] ?> (<?= $s['ustat_admin_action'] ?>)</td>
    <td><?= $s['ustat_admin_comment'] ?></td>
  </tr>
  <? endforeach ; ?>
</table>