<?#Страница шаблона счета на %30-ю оплату заказа#?>
<html>
<head>
<style>
html, body {
margin: 0;
padding: 0;
}
.bill {
/*background: url('/tpl/givena/images/bill.png') no-repeat;*/
width: 744px;
padding: 40px 118px 0px 51px;
}
p {margin: 0;}
</style>
</head>
<body onload="print()">
<? if ($data) : ?>

<?
#-- если выбрал доставку почтой или транспортной компанией - то это 100% предоплата,
#-- если выбрал доставку курьером или самовывоз, то это предоплата 30%
global $pre_pay;
if (preg_match('~курьер~iu', $data['b_deliv']) || preg_match('~самовывоз~iu', $data['b_deliv']))
    $koef = $pre_pay/100;
else
    $koef = 1;

#-- сумма к оплате, если указана администратором.
/*if ($data['module_basket_item_summ2']>0)
{
    $summ = $data['module_basket_item_summ2'];
    $koef = 1;
}
else*/
    $summ = $data['summ']*$koef;
?>
<div class="bill">
<p style="margin: 3px 0px 20px 0px;font-size: 14px;text-align: center;line-height: 14px;">Оплата данного счета означает согласие с условиями поставки товара. Уведомление об оплате обязательно, в противном<br />
случае не гарантируется наличие товара на складе. Товар отправляется Транспортной Компанией, Почтой России,<br />
Курьерской службой или самовывозом по факту прихода денег на р/с Поставщика. Стоимость услуг по доставке товара<br />
оплачивает получатель. Обращаем Ваше внимание! Интернет-магазин не несет ответственности за работу и сроки доставки<br />
Почты России и Транспортной Компании. Все риски по доставке заказа переходят на покупателя по факту передачи товара<br />
продавцом Почте России или Транспортной Компании.</p>

<!--table border="1px" style="border: 1px;border-spacing: 0;width: 738px;height: 123px;">
		<tr>
			<td colspan="2" rowspan="2" style="width: 264px;">
				<p>ЗАО «КРЕДИТ ЕВРОПА БАНК»</p>
        <p>Банк получателя </p>
			</td>
			<td>
				<p>БИК </p>
			</td>
			<td rowspan="2">
				<p>044525767<br>40702810805800007594</p>
			</td>
		</tr>
		<tr>
			<td>
				<p>Сч. № </p>
			</td>
		</tr>
		<tr>
			<td>
				<p>ИНН 7718878718 </p>
			</td>
			<td>
				<p>КПП 771801001 </p>
			</td>
			<td rowspan="2" style="vertical-align: top;">
				<p>Корр.счет. № </p>
			</td>
			<td rowspan="2" style="vertical-align: top;">
				<p>30101810900000000767 </p>
			</td>
		</tr>
		<tr>
			<td>
				<p>ООО "Сатурн" </p>
				<p>Получатель </p>
			</td>
            <td></td>
		</tr>
</table-->

<p style="margin-top: 19px;font-size: 22px;font-weight: bold;font-family: arial;border-bottom: 3px solid;padding-bottom: 13px;">Счет на оплату № <?= $data['module_basket_item_id'] ?> от <?= date('d.m.Y') ?> г. </p>
<table>
		<tr>
			<td style="vertical-align: top;padding-top: 6px;width: 92px;">
				<p>Поставщик: </p>
			</td>
			<td style="padding-top: 6px;">
				<p><b>Индивидуальный предприниматель  Вьюгина  Елена Анатольевна,<br>ИНН 771306030537, тел.: 8-499-504-16-22<br>
                        Расч.счет: 40802810210090000749 в ЗАО «БАНК ИНТЕЗА» г.Москва ,<br>
                        БИК 044525922<br>Корр.счет:30101810800000000922
                    </b>
                </p>
			</td>
		</tr>
		<tr>
			<td style="vertical-align: top;padding-top: 10px;">
				<p>Покупатель: </p>
			</td>
			<td style="padding-top: 4px;">
				<p><b><?= $data['b_fio'] ?>, <?= $data['b_adress'] ?>,<br>тел.: <?= $data['b_phone'] ?> </b></p>
			</td>
		</tr>
</table>

<table border="1" style="border: 0px;border-spacing: 0;width: 741px;margin-top: 6px;">
		<tr style="font-weight: bold;">
			<td style="text-align: center;">
				<p>№</p>
			</td>
			<td style="text-align: center;">
				<p>Товары (работы, услуги) </p>
			</td>
			<td style="text-align: center;width: 60px;">
				<p>Кол-во </p>
			</td>
			<td style="text-align: center;width: 60px;">
				<p>Ед. </p>
			</td>
			<td style="text-align: center;width: 100px;">
				<p>Цена </p>
			</td>
			<td style="text-align: center;width: 100px;">
				<p>Сумма </p>
			</td>
		</tr>

    <? $k=0; foreach ($data['items'] as $i) : ?>
		<tr>
			<td>
				<p><?= (++$k) ?></p>
			</td>
			<td>
				<p><?= $i['f_title'] . ($i['f_lat']!=''?' ('.$i['f_lat'].')':'') ?><br><?= $i['f_group'] ?></p>
			</td>
			<td style="text-align: right;">
				<p><?= $i['cnt'] ?></p>
			</td>
			<td>
				<p>шт </p>
			</td>
			<td style="text-align: right;">
				<p><?= number_format($i['price'], 2, ',', ' ') ?></p>
			</td>
			<td style="text-align: right;">
				<p><?= number_format($i['price']*$i['cnt'], 2, ',', ' ') ?></p>
			</td>
		</tr>
    <? endforeach ; ?>

		<tr>
			<td colspan="3" style="border: none;">  </td>
			<td colspan="2" style="border: none;text-align: right;">
				<p>Итого: <!--?= number_format($data['summ'], 2, ',', ' ') ?--></p>
			</td>
			<td style="border: none;text-align: right;">
				<p><strong><?= number_format($data['summ'], 2, ',', ' ') ?></strong></p>
			</td>
  	</tr>
		<tr>
			<td colspan="3" style="border: none;">  </td>
			<td colspan="2" style="border: none;text-align: right;">
				<p>Без налога (НДС) </p>
			</td>
			<td style="border: none;text-align: right;">
				<p><strong>-</strong></p>
			</td>
		</tr>
		<tr>
			<td colspan="3" style="border: none;">  </td>
			<td colspan="2" style="border: none;text-align: right;">
				<p>Всего к оплате<?= ($koef==1?'':' '.$pre_pay.'%') ?>:</p>
			</td>
			<td style="border: none;text-align: right;">
				<p><strong><?= number_format($summ, 2, ',', ' ') ?></strong></p>
			</td>
		</tr>
</table>

<p>Всего наименований <?= $k ?> на сумму <?= number_format($summ, 2, ',', ' ') ?> руб. </p>
<p style="border-bottom: 3px solid;padding-bottom: 10px;margin-bottom: 5px;"><b><?= mb_ucfirst(num2text($summ), 'utf-8') ?></b></p>

<table>
    <tr>
			<td>
				<p><b>Руководитель:</b>  <span style="text-decoration: underline;font-size: 12px;">                                                                 Вьюгин М.В.</span></p>
			</td>
			<td>
				<p>  <b>Бухгалтер:</b>  <span style="text-decoration: underline;font-size: 12px;">                                                                  Вуколов А.Д.</span></p>
			</td>
		</tr>
</table>
</div>
<? else : ?>
<p>Неверный номер заказа!</p>
<? endif ; ?>
</body>
</html>