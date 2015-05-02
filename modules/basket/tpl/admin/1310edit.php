<div style="width: 1000px;">
<div class="title-pop ui-widget-header">
	<div class="title-pop-name"><?= $this->getClassTitle() ?> / ID: <?= $this->tm_id ?> (Изменен: <?= dateFormat($data['module_basket_date_update'], true) ?>)</div>
	<div class="title-pop-close"><img src="/admin/img/close.png" alt="" style="float: left;"><input type="button" value="Закрыть" class="admin-popup-window-button-close" /></div>
</div>

<div class="admin-popup-window-form">
<form action="" method="post">
<input type="hidden" name="admin_action" value="save">
<input type="hidden" name="ajax" value="1">
<input type="hidden" name="cl" value="<?= $cl ?>">
<input type="hidden" name="tm" value="<?= $tm_id ?>">

<script>$(function(){ $( "#tabs" ).tabs({ active: <?= ($cur_st-1) ?> }); });</script>

<div id="tabs">
    <ul>
    <?foreach ($status as $s_id=>$s_t):?>
        <li><a href="#tabs-<?= $s_id ?>"><?= $s_t ?></a></li>
    <?endforeach;?>
        <li><a href="#tabs-opt">Настройки</a></li>
        <li><a href="#tabs-help">Справка</a></li>
    </ul>
	<p class="clear"></p>
	<br>
<?foreach ($status as $s_id=>$s_t):?>

  <div id="tabs-<?= $s_id ?>">
	  <div class="basket_history_new_scroll">

    <? if (sizeof($data[$s_id])) : ?>
      <table border="0" class="basket_history_new" style="width: 950px;">
        <tr  style="font-weight: bold;">
          <td style="width:50px;">Номер</td>
          <td style="width:110px;">Дата</td>
          <td style="width:60px;">Товаров</td>
          <td style="width:80px;">Сумма к оплате</td>
          <td style="width:80px;">Оплачено</td>
          <td style="width:80px;">Статус</td>
        </tr>
      <? foreach($data[$s_id] as $z) : ?>

        <tr style="cursor:pointer;">
          <td onclick="$(this).parent().next().toggle();"><?= $z['module_basket_item_id'] ?></td>
          <td onclick="$(this).parent().next().toggle();"><?= dateFormat($z['module_basket_item_date_update'], true) ?></td>
          <td onclick="$(this).parent().next().toggle();"><?= $z['cnt'] ?></td>
          <td onclick="$(this).parent().next().toggle();"><?= $z['summ'] ?></td>
          <td><input type="text" style="width:50%;" id="dop-summ-<?= $z['module_basket_item_id'] ?>" value="<?= ($z['module_basket_item_summ2']>0?$z['module_basket_item_summ2']:'') ?>" />&nbsp;<input type="button" value="S" onclick="saveDopSumm(<?= $z['module_basket_item_id'] ?>);"><div style="display:none; color:#0000ff;" id="ok-<?= $z['module_basket_item_id'] ?>"></div></td>
          <td><select onchange="newStatusElement(<?= $z['module_basket_item_id'] ?>, $(this).val());">
            <? foreach($status as $s2_id=>$s2_t) : ?>
              <option value="<?= $s2_id ?>"<?= ($s_id==$s2_id?' selected':'') ?>><?= $s2_t ?></option>
            <? endforeach; ?>
            </select></td>
        </tr>
        <tr style="display:none;">
          <td colspan="2">
            <? foreach ($z['client'] as $c) : ?>
            <p><?= $c['title'] ?>: <?= $c['value'] ?></p>
            <? endforeach ; ?>
          </td>
          <td colspan="4">
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

      <?= $pages[$s_id] ?>

    <? else : ?>
      <p>Список пуст!</p>
    <? endif ; ?>

    <br>
    </div>
  </div>
<?endforeach;?>

<script>
$(document).ready(function(){
  nextPageList = function(p, st)
  {
    $.ajax({
      url: '/_ajax/',
      data: { d:'edit_block', cl:'<?= $this->getClassName() ?>', tm:'<?= $this->tm_id ?>', item_id:0, p:p, cur_status:st },
      type: "POST",
      cache: false,
      success: function(data)
      {
        $('.admin-popup-window').html(data);
        showPopup();
      },
      beforeSend: function()
      {
        $('.admin-popup-window').html('<img src="/admin/img/load.gif" border="0" id="preload-img" />');
      }
    });
    return false;
  }

  saveDopSumm = function(oid)
  {
    if (oid>0)
    {
      $('#ok-'+oid).hide().text('');
      $.ajax({
        url: '/_ajax/',
        data: { d:'edit_block', cl:'<?= $this->getClassName() ?>', tm:'<?= $this->tm_id ?>', item_id:oid, admin_action:'upd_summ', summ:$('#dop-summ-'+oid).val(), p:1},
        type: "POST",
        cache: false,
        success: function(data)
        {
          if (data=='ok')
            $('#ok-'+oid).show(200).text('Сохранено!');
          else
            $('#ok-'+oid).show(200).text('ОШИБКА!');
        },
        beforeSend: function(){}
      });
    }
    return false;
  }

  newStatusElement = function(oid, st)
  {
    if (confirm('Вы уверены, что хотите перевести заказ в другой статус?'))
    {
      $.ajax({
        url: '/_ajax/',
        data: { d:'edit_block', cl:'<?= $this->getClassName() ?>', tm:'<?= $this->tm_id ?>', item_id:oid, admin_action:'new_status', status:st, p:1},
        type: "POST",
        cache: false,
        success: function(data)
        {
          $('.admin-popup-window').html(data);
          showPopup();
        },
        beforeSend: function()
        {
          $('.admin-popup-window').html('<img src="/admin/img/load.gif" border="0" id="preload-img" />');
        }
      });
    }
    return false;
  }
});
</script>


    <div id="tabs-opt">
		<table>
      <tr>
				<td>
					Список E-mail (сообщение о новом заказе):
				</td>
				<td>
					<input type="text" name="emails" value="<?= $data['module_basket_emails'] ?>" />
				</td>
			</tr>
		</table>
    </div>

    <div id="tabs-help">
  		<p><?= $APP->main_c_getHelpText($cl); ?></p>
    </div>
</div>

<br>
<input type="submit" value="Сохранить" class="admin-popup-window-button-save">
<input type="button" value="Закрыть" class="admin-popup-window-button-close" />

</form>
</div>
</div>