<?#Шаблон страницы заказа#?>

<input type="button" value="Закрыть" name="butt2" onclick="$('#basket-order-list01').hide(666).html('');">

<h2>Корзина</h2>

<? if (sizeof($data['order']['items'])>0) : ?>

<form action="" method="post">
<input type="hidden" name="tm" value="<?= $data['module_basket_type'] ?>" />
<input type="hidden" name="action" value="basket_go" />

<table border="1" cellpadding="5">
  <tr>
    <td>Картинка</td>
    <td>Название/лат.</td>
    <td>Цена за ед. (руб.)</td>
    <td>Кол-во</td>
    <td>Общая цена (руб.)</td>
    <td>Удалить</td>
  </tr>
<? foreach($data['order']['items'] as $oid=>$it) : ?>

  <tr id="order-string-<?= $oid ?>">
    <td><img src="/files/resize_cache<?= $it['f_file_data']['dirname'] . '50x50-crop_'.$it['f_file_data']['name'] ?>" alt="" style="border:0;"/></td>
    <td><?= $it['f_title'] ?><br><small><?= $it['f_lat'] ?></small> </td>
    <td><?= number_format($it['f_price'], 2, ',', ' ') ?></td>
    <td>
      <input type="text" maxlength="3" id="counter-b<?= $oid ?>" name="cnt[<?= $oid ?>]" value="<?= $it['cnt'] ?>" style="width:30px;">
      <a onclick="counterup(<?= $oid ?>, 'counter-b');" href="javascript://"><img alt="Больше" class="up" src="/tpl/biz3/images/000.gif"></a>
      <a onclick="counterdown(<?= $oid ?>, 'counter-b');" href="javascript://"><img alt="Меньше" class="down" src="/tpl/biz3/images/000.gif"></a>
    </td>
    <td><?= number_format($it['cnt']*$it['f_price'], 2, ',', ' ') ?></td>
    <td><a href="javascript://" onclick="return deleteItem(<?= $oid ?>, '<?= $data['module_basket_type'] ?>');">удалить</a></td>
  </tr>

<? endforeach ; ?>
</table>

<p>Всего товаров: <?= $data['order']['cnt'] ?> шт.</p>
<p>Итоговая цена: <?= number_format($data['order']['summ'], 2, ',', ' ') ?> руб.</p>

<input type="button" value="Далее" name="butt1" onclick="$('#basket-order-form01').show(666);">

<div id="basket-order-form01" style="display:none;">
  <? foreach ($data['client'] as $c) : ?>
  <p><?= $c['title'] ?>:&nbsp;<?= $c['field'] ?></p>
  <? endforeach ; ?>

  <input type="submit" value="Оформить заказ" name="butt3" onclick="return testDataBasket();">
</div>

</form>

<? else : ?>

  <p>Ваша корзина пуста!</p>

<? endif ; ?>