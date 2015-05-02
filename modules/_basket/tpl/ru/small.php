<?#Шаблон блока корзины#?>

<script src="/tpl/biz3/js/basket.js" type="text/javascript"></script>

<h2>Корзина</h2>

<p style="margin:0; paddin:0;">

<? if ($data['order']['cnt']>0) : ?>
  <a href="javascript://" onclick="return ShowBasketOrder('<?= $data['module_basket_type'] ?>');">Товаров <?= $data['order']['cnt'] ?> шт. на <?= number_format($data['order']['summ'], 2, ',', ' ') ?> руб.</a>
<? else : ?>
  Ваша корзина пуста!
<? endif ; ?>

</p>

<div id="basket-order-list01" style="display:none;position:fixed; top:10px; left:200px; padding:10px; background-color:#fff; border:none; z-index:10000;"></div>