<?#Страница списка заказов пользователя#?>

<? include('./tpl/'.$_SESSION['_SITE_']['theme'].'/ru/profile/inc_menu.php'); ?>

<p><b>Список заказов</b></p>

<? if (sizeof($orders)>0) : ?>
  <table border="1">
    <tr>
      <td>№</td>
      <td>Статус</td>
      <td>Дата</td>
      <td>Сумма</td>
      <td>Состав</td>
    </tr>
  <? foreach ($orders as $o) : ?>
    <tr>
      <td><?= $o['module_basket_item_id'] ?></td>
      <td><?= $o['module_basket_status_title'] ?></td>
      <td><?= date('d.m.Y H:i', strtotime($o['module_basket_item_date_update'])) ?></td>
      <td><?= $o['summ'] ?> руб.</td>
      <td>
        <u>Всего <b><?= $o['cnt'] ?></b> наименования:</u>
        <br>
        <? $k=0; foreach ($o['items'] as $it) : ?>
        <p><?= ++$k ?>) <?= $it['f_title'] ?> (<?= $it['cnt'] ?> шт., <?= $it['price'] ?> руб.)</p>
        <? endforeach ; ?>
      </td>
    </tr>
  <? endforeach ; ?>
  </table>

<? endif ; ?>