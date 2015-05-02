<?#Шаблон блока корзины#?>

<script src="/tpl/givena/js/basket.js" type="text/javascript"></script>


<? if ($data['order']['cnt']>0) : ?>
  <span>
	В <a href="javascript://" onclick="return ShowBasketOrder('<?= $data['module_basket_type'] ?>');">корзине</a> <i id="small_basket_cnt"><?= $data['order']['cnt'] ?></i> товаров на сумму <i id="small_basket_sum"><?= number_format($data['order']['summ'], 0, ',', ' ') ?></i> Р
  </span>
  <a href="javascript://" onclick="return ShowBasketOrder('<?= $data['module_basket_type'] ?>');"><img src="/tpl/givena/images/000.gif" class="goto-basket" alt="" /></a>
<? else : ?>

<span>
  Ваша корзина пуста
</span>
<? endif ; ?>


<?/*?>
<div id="basket-order-list01" style="display:none;position:absolute; top:10px; min-width: 900px;left:30px;box-shadow: 0 0 15px 0 #333333; padding:10px; background-color:#e5f5bb; border:none; z-index:9998;"></div>
<?*/?>