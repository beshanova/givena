<?#Страница списка заказов пользователя#?>

<? $act='index'; include('./tpl/'.$_SESSION['_SITE_']['theme'].'/ru/profile/inc_menu.php'); ?>

<h2>Список заказов</h2>
<br>
<!--b>Уважаемые покупатели! На данный момент у нас изменились реквизиты. Новые реквизиты вы получите на электронный адрес указанный при оформлении заявки.</b>
<br><br-->
<? if (sizeof($orders)>0) : ?>
  <table border="0" class="orders">
    <tr class="border-top">
      <td style="width: 28px;color: #0a5100;font-weight: 700;">№</td>
      <td style="width: 110px;color: #0a5100;font-weight: 700;">Дата</td>
      <td style="color: #0a5100;font-weight: 700;">Описание заказа</td>
      <td style="width: 65px;color: #0a5100;font-weight: 700;">Сумма к оплате</td>
      <td style="width: 100px;color: #0a5100;font-weight: 700;">Оплачено</td>
      <td style="width: 75px;color: #0a5100;font-weight: 700;">Статус</td>
      <td style="width: 80px;color: #0a5100;font-weight: 700;">Счет</td>
    </tr>
  <? foreach ($orders as $o) : ?>
    <tr>
      <td><?= $o['module_basket_item_id'] ?></td>
      <td><?= date('d.m.Y H:i', strtotime($o['module_basket_item_date_update'])) ?></td>
        <td>
            <a href="javascript://" onclick="$('p.items-<?= $o['module_basket_item_id'] ?>').toggle();">Всего <b><?= $o['cnt'] ?></b> товаров</a>
            <? $k=0; foreach ($o['items'] as $it) : ?>
                <p class="auth-order-item items-<?= $o['module_basket_item_id'] ?> li"><?= $it['f_title'] ?> (<?= $it['cnt'] ?> шт., <?= $it['price'] ?> руб.)</p>
            <? endforeach ; ?>
        </td>
      <td style="font-style: italic;"><?= $o['summ'] ?> руб.</td>
      <!--td style="font-style: italic;"><?= ($o['module_basket_item_summ2']>0?$o['module_basket_item_summ2']: ((preg_match('~курьер~iu', $o['b_deliv']) || preg_match('~самовывоз~iu', $o['b_deliv'])&&!$o['module_basket_item_summ2'])?round($o['summ']*($pre_pay/100),2):$o['summ']) ) ?> руб.</td-->
       <td style="font-style: italic;"><?= ($o['module_basket_item_summ2']>0?$o['module_basket_item_summ2']:0); ?> руб.</td>
      <td><?= $o['module_basket_status_title'] ?></td>


      <td>		
      <? if ($o['module_basket_status_id']<=3) : ?>
        <a target="_blank" href="/profile/?cl=<?= $this->getClassName() ?>&tm=<?= $this->tm_id ?>&item_id=<?= $o['module_basket_item_id'] ?>&a=pay" >
            Счет<!--?=((preg_match('~курьер~iu', $o['b_deliv']) || preg_match('~самовывоз~iu', $o['b_deliv'])&&!$o['module_basket_item_summ2'])?' '.$pre_pay.'%':'')?-->
        </a>
      <? else : ?>
        -
      <? endif ; ?>
	  
      </td>
    </tr>
  <? endforeach ; ?>
  </table>

<? endif ; ?>