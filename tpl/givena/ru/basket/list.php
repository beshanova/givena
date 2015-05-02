<?#Шаблон страницы заказа#?>

<!--input type="button" value="" name="butt2" class="basket_close_but" onclick="closePopupFrontEnd();"-->
<?
//printarray($data);
?>
<h2>Корзина</h2>

<?$is_met_rosy=0;?>
<? if (sizeof($data['order']['items'])>0) : ?>

<form action="" method="post" id="form-basket-01">
<input type="hidden" name="tm" value="<?= $data['module_basket_type'] ?>" />
<input type="hidden" name="action" value="basket_go" />
<div class="basket_list_item">
<table border="0" cellpadding="5" cellspacing="0">
  <tr style="font-weight: bold;color: #0A5100;">
    <td style="width:100px;padding-left:10px">Товар</td>
    <td style="width:320px;"></td>
    <td style="width: 90px;" class="textalignr">Цена</td>
	  <td style="width: 15px;"></td>
    <td class="textalignc" style="width: 145px;">Кол-во</td>
    <td style="width:95px;text-align: right;">Сумма</td>
    <td style="width:75px;text-align: center;">Удалить</td>
  </tr>
<?
//printarray($data['order']['items']);
foreach($data['order']['items'] as $oid=>$it) : ?>
<?
//if ($it['category']=='Розы') $is_met_rosy=1;
?>
  <tr id="order-string-<?= $oid ?>">
    <td style="padding-left:10px;">
		<a href="/catalog/<?= $it['url'] ?>">
			<img src="/files/resize_cache<?= $it['f_file_data']['dirname'] . '95x60-crop_'.$it['f_file_data']['name'] ?>" alt="" style="border:0;"/>
		</a>
	</td>
    <td class="name">
		<a href="/catalog/<?= $it['url'] ?>"><?= $it['f_title'] ?></a><br><small><? eval("print PR".$it['pr'].";"); ?></small>
    <? if ($it['is_item']==0) : ?>
    <br><small><b>Нет в наличии</b></small>
    <? endif; ?>
	</td>
    <td class="price textalignr" style="font-weight: bold;">
		<?= number_format($it['f_price'], 0, '.', ' ') ?> руб.
	</td>
    <td></td>
	<td class="counter textalignc">
		<a onclick="counterdown('<?= $oid ?>', 'counter-b');" href="javascript://"><img alt="Меньше" class="down" src="/tpl/givena/images/down.png"></a>
		<input type="text" maxlength="3" id="counter-b<?= $oid ?>" name="cnt[<?= $oid ?>]" value="<?= $it['cnt'] ?>" price="<?= $it['f_price'] ?>" class="counts-tr-00" cpid="<?= $oid ?>">
		<a onclick="counterup('<?= $oid ?>', 'counter-b');" href="javascript://"><img alt="Больше" class="up" src="/tpl/givena/images/up.png"></a>
    </td>
    <td class="price textalignr" style="font-weight: bold;">
		  <span id="summ-tr-<?= $oid ?>"><?= number_format($it['cnt']*$it['f_price'], 0, '.', ' ') ?></span> <span>руб.</span>
	  </td>
    <td style="text-align: center;">
      <a href="javascript://" onclick="return deleteItem('<?= $oid ?>', '<?= $data['module_basket_type'] ?>');" class="del_item">
        <img alt="Удалить" src="/tpl/givena/images/del.png">
      </a>
	  </td>
  </tr>

<? endforeach ; ?>
  <tr>

    <td colspan="3" class="no-border">
		<input type="button" value="" name="butt1" onclick="return ShowBasketConfirm('<?= $data['module_basket_type'] ?>', <?=$is_met_rosy?1:0;?>);" class="basket_next">
	</td>
    <td colspan="2" style="text-align: right;padding-top: 16px;" class="no-border summ_all">Товаров: <span id="cnt-all-00" class="price"><?= (int)$data['order']['cnt'] ?></span> <span>шт.</span></td>
    <td colspan="2" style="text-align: right;padding-top: 16px;" class="no-border summ_all">
		Сумма: <span id="summ-all-00" class="price"><?= number_format($data['order']['summ'], 0, '.', ' ') ?></span> <span>руб.</span>
	</td>
  </tr>
</table>
</div>
<br />

<br />
<div id="basket-order-form01" style="display:none;">
<h3>Оформление заказа</h3>
<div class="ofz">

<? if ($_SESSION['_SITE_']['profiledata']['module_profile_item_id']>0) : ?>
<p>Вы авторизованы на сайте. После оформления заказа он появится у Вас в личном кабинете.</p>
<? else : ?>
<p>При вводе данных ниже, Вы будет автоматически зарегистрированы. Данные по профилю и заказу будут высланы на указанную почту.<br>
Если Вы уже зарегистрированы, <a href="javascript://" onclick="$('#auth-e-01').val($('#b-b_email').val()); closePopupFrontEnd(); $('#auth-p-01').focus();">авторизуйтесь</a>!</p>
<? endif ; ?>

	<table cellspacing="0">
    <tr>
			<td>Доставка:</td>
			<td><?= implode('', $data['client']['b_deliv']['field']) ?></td>
			<td></td>
			<td>Оплата:</td>
			<td><?= implode('', $data['client']['b_buy']['field']) ?></td>

		</tr>
		<tr>
			<td style="width:80px;">ФИО<span class="red">*</span>:</td>
			<td><?= $data['client']['b_fio']['field'] ?></td>
			<td style="width:40px;"></td>
			<td rowspan="5" style="width:90px;vertical-align: top;">
				Комментарий:
				<br />
				<br />
				<span>Вы можете
				оставить свои
				пожелания.</span>
			</td>
			<td rowspan="3"><?= ($data['client']['b_comment']['field']);?></td>
		</tr>
    <?/*
    <tr>
			<td>Имя<span class="red">*</span>:</td>
			<td><?= $data['client']['b_name']['field'] ?></td>
			<td></td>
		</tr>
    */?>
    <tr>
			<td>E-mail<span class="red">*</span>:</td>
			<td><?= $data['client']['b_email']['field'] ?></td>
			<td></td>
		</tr>
		<tr>
			<td>Телефон<span class="red">*</span>:</td>
			<td><?= ($data['client']['b_phone']['field']);?></td>
			<td></td>
		</tr>
		<tr id="tr-adress-01">
			<td>Адрес<span class="red">*</span>:</td>
			<td><?= ($data['client']['b_adress']['field']);?></td>
			<td></td>
		</tr>

	</table>
</div>

  <div class="basket_send_div2" style="display:none;"><div style="text-align: center;"><input type="submit" name="butt3" onclick="return testDataBasket(0);" value="" class="prod"><input type="button" value="" onclick="$('#auth-e-01').val($('#b-b_email').val()); closePopupFrontEnd(); $('#auth-p-01').focus();" class="avtor"></div></div>

  <div class="basket_send_div"><input type="submit" value="" name="butt3" onclick="return testDataBasket(<?= ($_SESSION['_SITE_']['profiledata']['module_profile_item_id']>0 ? '0' : '1') ?>);" class="basket_of basket_send"></div>
</div>

</form>


<? else : ?>

  <p>Ваша корзина пуста!</p>

<? endif ; ?>