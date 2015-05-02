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
		<li><a href="#tabs-search">Поиск</a></li>
        <li><a href="#tabs-opt">Настройки</a></li>
        <li><a href="#tabs-help">Справка</a></li>
    </ul>
	<p class="clear"></p>
	<br>
<?foreach ($status as $s_id=>$s_t):?>

  <div id="tabs-<?= $s_id ?>" class="block-tab">
	  <div class="basket_history_new_scroll">

    <? if (sizeof($data[$s_id])) : ?>
        <?
        print $this->ApplyTemplateAdmin('table_order.php', array('data'=>$data[$s_id], 'status'=>$status, 's_id'=>$s_id), $this->getClassName());
        ?>

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
        data: { d:'edit_block', cl:'<?= $this->getClassName() ?>', tm:'<?= $this->tm_id ?>', item_id:oid, admin_action:'upd_summ', summ:$('#tabs .block-tab:visible #dop-summ-'+oid).val(), p:1},
        type: "POST",
        cache: false,
        success: function(data)
        {
          if (data==1){
              $('#tabs .block-tab:visible #ok-'+oid).show(200).text('Сохранено!');
          }
          else{
              $('#tabs .block-tab:visible #ok-'+oid).show(200).text('ОШИБКА!');
          }

        },
        beforeSend: function(){}
      });
    }
    return false;
  }

  SearchByNumOrder  = function()
  {
    var searchid = $('#searchfield').val();
    if (searchid)
    {
      $.ajax({
        url: '/_ajax/',
        data: { d:'edit_block', cl:'<?= $this->getClassName() ?>', tm:'<?= $this->tm_id ?>', admin_action:'searchbynum', searchid:searchid, p:1},
        type: "POST",
        cache: false,
        success: function(data)
        {
            $('#searchres').html(data);
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

	<div id="tabs-search" class="block-tab">
        <p>Поиск по номеру заказа<p>
        <table>
            <tr>
                <td>
                    <input type="text" id="searchfield"/>
                </td>
                <td>
                    <input class="admin-popup-window-button-save" type="button" value="Найти" onclick="return SearchByNumOrder();">
                </td>
            </tr>
        </table>

        <div id="searchres"></div>
    </div>

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