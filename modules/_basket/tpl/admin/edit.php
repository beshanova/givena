<div class="title-pop ui-widget-header">
	<div class="title-pop-name"><?= $this->getClassTitle() ?> / ID: <?= $this->tm_id ?> (Изменен: <?= dateFormat($data['module_basket_date_update'], true) ?>)</div>
	<div class="title-pop-close"><img src="/admin/img/close.png" alt="" style="float: left;"><input type="button" value="Закрыть" class="admin-popup-window-button-close" /></div>
</div>

<div class="admin-popup-window-form" style="width: 1000px;">
<form action="" method="post">
<input type="hidden" name="admin_action" value="save">
<input type="hidden" name="ajax" value="1">
<input type="hidden" name="cl" value="<?= $cl ?>">
<input type="hidden" name="tm" value="<?= $tm_id ?>">

<script>$(function(){ $( "#tabs" ).tabs(); });</script>

<div id="tabs">
    <ul>
        <li><a href="#tabs-0">Новые заказы</a></li>
        <li><a href="#tabs-1">Старые заказы</a></li>
        <li><a href="#tabs-2">Настройки</a></li>
        <li><a href="#tabs-3">Справка</a></li>
    </ul>
	<p class="clear"></p>
	<br>

    <div id="tabs-0">
	<div class="basket_history_new_scroll">
    
    <? if (sizeof($data['order_new'])) : ?>
      <table border="0" class="basket_history_new">
        <tr  style="font-weight: bold;">
          <td style="width:50px;">Номер</td>
          <td style="width:110px;">Дата</td>
          <td style="width:60px;">Товаров</td>
          <td style="width:80px;">Сумма (руб.)</td>
        </tr>
      <? foreach($data['order_new'] as $z) : ?>

        <tr onclick="$(this).next().toggle();" style="cursor:pointer;">
          <td><?= $z['module_basket_item_id'] ?></td>
          <td><?= dateFormat($z['module_basket_item_date_update'], true) ?></td>
          <td><?= $z['cnt'] ?></td>
          <td><?= $z['summ'] ?></td>
        </tr>
        <tr style="display:none;">
          <td colspan="2">
            <? foreach ($z['client'] as $c) : ?>
            <p><?= $c['title'] ?>: <?= $c['value'] ?></p>
            <? endforeach ; ?>
          </td>
          <td colspan="2">
            <? foreach ($z['items'] as $i) : ?>
            <p>
              <? foreach ($i as $k=>$t) : ?>
                <? if (preg_match('~^f_~is', $k) && !is_array($t)) : ?>
                    <?= $t ?>,
                <? endif ; ?>
              <? endforeach ; ?>
              (<?= $i['price'] ?> руб., <?= $i['cnt'] ?> шт.)
            </p>
            <? endforeach ; ?>
          </td>
        </tr>

      <? endforeach ; ?>
      </table>
    <? else : ?>
      <p>Список пуст!</p>
    <? endif ; ?>

    <br>

    </div>
    </div>
	<div id="tabs-1">
	<div class="basket_history_old_scroll">
    <? if (sizeof($data['order_old'])) : ?>
      <table border="0" class="basket_history_old">
        <tr  style="font-weight: bold;">
          <td>Номер</td>
          <td>Дата</td>
          <td>Товаров</td>
          <td>Сумма (руб.)</td>
        </tr>
      <? foreach($data['order_old'] as $z) : ?>

        <tr onclick="$(this).next().toggle();" style="cursor:pointer;">
          <td><?= $z['module_basket_item_id'] ?></td>
          <td><?= dateFormat($z['module_basket_item_date_update'], true) ?></td>
          <td><?= $z['cnt'] ?></td>
          <td><?= $z['summ'] ?></td>
        </tr>
        <tr style="display:none;">
          <td colspan="2">
            <? foreach ($z['client'] as $c) : ?>
            <p><?= $c['title'] ?>: <?= $c['value'] ?></p>
            <? endforeach ; ?>
          </td>
          <td colspan="2">
            <? foreach ($z['items'] as $i) : ?>
            <p>
              <? foreach ($i as $k=>$t) : ?>
                <? if (preg_match('~^f_~is', $k) && !is_array($t)) : ?>
                    <?= $t ?>,
                <? endif ; ?>
              <? endforeach ; ?>
              (<?= $i['price'] ?> руб., <?= $i['cnt'] ?> шт.)
            </p>
            <? endforeach ; ?>
          </td>
        </tr>

      <? endforeach ; ?>
      </table>
    <? else : ?>
      <p>Список пуст!</p>
    <? endif ; ?>
    </div>
    </div>
    <div id="tabs-2">
		<table>
      <tr>
				<td>
					Список E-mail (сообщене о новом заказе):
				</td>
				<td>
					<input type="text" name="emails" value="<?= $data['module_basket_emails'] ?>" />
				</td>
			</tr>
		</table>
    </div>

    <div id="tabs-3">
  		<p><?= $APP->main_c_getHelpText($cl); ?></p>
    </div>
</div>

<br>
<input type="submit" value="Сохранить" class="admin-popup-window-button-save">
<input type="button" value="Закрыть" class="admin-popup-window-button-close" />

</form>
</div>