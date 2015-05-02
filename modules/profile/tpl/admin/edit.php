<div class="title-pop ui-widget-header">
	<div class="title-pop-name"><?= $this->getClassTitle() ?></div>
	<div class="title-pop-close"><img src="/admin/img/close.png" alt="" style="float: left;"><input type="button" value="Закрыть" class="admin-popup-window-button-close" /></div>
</div>

<script>
$(function() {
    $( "#tabs" ).tabs();
});
</script>

<div class="admin-popup-window-form">
<form action="" method="post">
<input type="hidden" name="a" value="save">
<input type="hidden" name="ajax" value="1">
<input type="hidden" name="cl" value="<?= $this->getClassName() ?>">
<input type="hidden" name="tm" value="<?= $this->tm_id ?>">
<div id="tabs">
  <ul>
    <li><a href="#tabs-1">Пользователи сайта</a></li>
    <li><a href="#tabs-1-1" id="p-orders-list-00">Заказы</a></li>
    <li><a href="#tabs-2">Настройки</a></li>
    <li><a href="#tabs-3">Справка</a></li>
  </ul>
	<p class="clear"></p>
	<br>

    <div id="tabs-1">

    <? if (sizeof($users)>0) : ?>
      <table class="admin-table-user-list" border="1">
        <tr class="tr-title">
          <td>E-mail</td>
          <? foreach ($fields as $f) : ?>
          <td><?= $f['title'] ?></td>
          <? endforeach ; ?>
          <td>Дата последнего действия</td>
          <td>Дата регистрации</td>
          <td>Заказы</td>
          <td>Операции</td>
        </tr>
        <? foreach ($users as $u) : ?>
        <tr<?= (!$u['module_profile_item_active'] ? ' class="tr-no-active"' : '') ?> id="user-list-edit-<?= $u['module_profile_item_id'] ?>">
          <td>
            <a href="mailto:<?= $u['module_profile_item_email'] ?>"><?= $u['module_profile_item_email'] ?></a>
            <input type="hidden" id="p-is-edit-<?= $u['module_profile_item_id'] ?>" name="is_edit[<?= $u['module_profile_item_id'] ?>]" value="0" />
          </td>
          <? foreach ($fields as $f) : ?>
          <td>
            <div class="p-td-text-<?= $u['module_profile_item_id'] ?>" at="<?= $f['COLUMN_NAME'] ?>"><?= $u[$f['COLUMN_NAME']] ?></div>
            <div class="p-td-edit-<?= $u['module_profile_item_id'] ?>" style="display:none;">
            <? if (is_array($f['field'])) : ?>
                <?= implode('<br />', preg_replace('~name="([a-z\d_\-]+)"~is', 'name="$1['.$u['module_profile_item_id'].']"', preg_replace('~id="(p\-[a-z\d_\-]+)"~i', 'id="$1-'.$u['module_profile_item_id'].'"', $f['field'])) ) ?>
            <? else : ?>
                <?= preg_replace('~name="([a-z\d_\-]+)"~is', 'name="$1['.$u['module_profile_item_id'].']"', preg_replace('~id="(p\-[a-z\d_\-]+)"~i', 'id="$1-'.$u['module_profile_item_id'].'"', $f['field'])) ?>
            <? endif ; ?>
            </div>
          </td>
          <? endforeach ; ?>
          <td><?= (strtotime($u['module_profile_item_date_update'])>0 ? date('d.m.Y H:i', strtotime($u['module_profile_item_date_update'])) : '-') ?></td>
          <td><?= (strtotime($u['module_profile_item_date_add'])>0 ? date('d.m.Y H:i', strtotime($u['module_profile_item_date_add'])) : '-') ?></td>
          <td><a href="javascript://" style="text-decoration:none; border-bottom:1px dashed;" onclick="return f_show_orders(<?= $u['module_profile_item_id'] ?>);">Перейти</a></td>
          <td>
            <div onclick="f_edit_user_profile(<?= $u['module_profile_item_id'] ?>);" class="user_list_edit_p tooltip"><span class="tooltip-span" style="display: none;">Нажмите на иконку, чтобы <br> редактировать</span></div>

            <div onclick="f_edit_active(<?= $u['module_profile_item_id'] ?>);" class="user_list_active tooltip"><span class="tooltip-span" style="display: none;">Нажмите на иконку, чтобы <br> активировать</span></div>

            <div onclick="f_edit_delete(<?= $u['module_profile_item_id'] ?>);" class="user_list_delete tooltip"><span class="tooltip-span" style="display: none;">Нажмите на иконку, чтобы <br> удалить</span></div>
          </td>
        </tr>
        <? endforeach ; ?>
      </table>

      <?= $pages_u ?>

      <br>
      <input type="submit" value="Сохранить" class="admin-popup-window-button-save">
      <input type="button" value="Закрыть" class="admin-popup-window-button-close" />

    <? else : ?>
      <p>Нет зарегистрированных пользователей!</p>
    <? endif ; ?>

    </div>


    <div id="tabs-1-1">

      <div class="basket_history_new_scroll">

      <? if (sizeof($orders)) : ?>
        <table border="0" class="basket_history_new">
          <tr  style="font-weight: bold;">
            <td style="width:50px;">Номер</td>
            <td style="width:110px;">Дата</td>
            <td style="width:60px;">Товаров</td>
            <td style="width:80px;">Сумма (руб.)</td>
            <td style="width:80px;">Статус</td>
          </tr>

        <? foreach($orders as $z) : ?>

          <tr style="cursor:pointer;">
            <td onclick="$(this).parent().next().toggle();"><?= $z['module_basket_item_id'] ?></td>
            <td onclick="$(this).parent().next().toggle();"><?= dateFormat($z['module_basket_item_date_update'], true) ?></td>
            <td onclick="$(this).parent().next().toggle();"><?= $z['cnt'] ?></td>
            <td onclick="$(this).parent().next().toggle();"><?= $z['summ'] ?></td>
            <td><select onchange="newStatusElementP(<?= $z['module_basket_item_id'] ?>, $(this).val());">
              <? foreach($status as $s2_id=>$s2_t) : ?>
                <option value="<?= $s2_id ?>"<?= ($z['module_basket_item_status']==$s2_id?' selected':'') ?>><?= $s2_t ?></option>
              <? endforeach; ?>
              </select></td>
          </tr>
          <tr style="display:none;">
            <td colspan="5">
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
        <? if (!isset($_REQUEST['uid'])) : ?>
        <p>Для просмотра заказов выберите пользователя!</p>
        <? else : ?>
        <p>У выбранного пользователя заказов нет!</p>
        <? endif ; ?>
      <? endif ; ?>

      <br>
      </div>

    </div>


    <div id="tabs-2">
		<table border="0" cellspacing="0" cellpadding="5">
			<tr>
				<td>E-mail адрес, с которого будут уходить сообщения о регистрации:</td>
				<td>
          <input type="text" name="email_from" value="<?= htmlspecialchars($data['module_profile_email_from']) ?>" />
				</td>
			</tr>
      <tr>
				<td>E-mail адреса (через ",") администраторов,<br>на которые будут приходить письма о регистрациях:</td>
				<td>
          <input type="text" name="emails" value="<?= htmlspecialchars($data['module_profile_emails']) ?>" />
				</td>
			</tr>
      <tr>
				<td>Тип регистрации пользователей:</td>
				<td>
          <select name="is_confirm">
            <option value="0"<?= ($data['module_profile_is_confirm']==0 ? ' selected' : '') ?>>подтверждает администратор</option>
            <option value="1"<?= ($data['module_profile_is_confirm']==1 ? ' selected' : '') ?>>подтверждает пользователь по ссылке в письме</option>
            <option value="2"<?= ($data['module_profile_is_confirm']==2 ? ' selected' : '') ?>>без подтверждения (профиль сразу активен)</option>
          </select>
				</td>
			</tr>
		</table>

    <br>
    <input type="submit" value="Сохранить" class="admin-popup-window-button-save">
    <input type="button" value="Закрыть" class="admin-popup-window-button-close" />

  </div>

  <div id="tabs-3">
    <?= $APP->main_c_getHelpText($cl); ?>
  </div>

</div>

</form>
</div>

<script>
$(document).ready(function(){

  nextPageList = function(p)
  {
    $.ajax({
      url: '/_ajax/',
      data: { d:'edit_block', cl:'<?= $this->getClassName() ?>', tm:'<?= $this->tm_id ?>', p:p },
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

  f_edit_user_profile = function(uid)
  {
    $('.p-td-text-'+uid).each(function(){
      var at = $(this).attr('at');
      $(this).hide();
      $('#p-'+at+'-'+uid).val($(this).text());
    });
    $('.p-td-edit-'+uid).show();
    $('#p-is-edit-'+uid).val(1);
  }

  f_edit_active = function(uid)
  {
    $.ajax({
      url: '/_ajax/',
      data: { cl:'<?= $this->getClassName() ?>', tm:'<?= $this->tm_id ?>', a:'edit_active', uid:uid },
      type: "POST",
      cache: false,
      success: function(data)
      {
        if (data=='ok')
        {
          if ($('#user-list-edit-'+uid).hasClass('tr-no-active'))
            $('#user-list-edit-'+uid).removeClass('tr-no-active');
          else
            $('#user-list-edit-'+uid).addClass('tr-no-active');
        }
        else
          alert('Ошибка!');
      },
      beforeSend: function() {}
    });
    return false;
  }

  f_edit_delete = function(uid)
  {
    if (confirm('Точно удалить пользователя?'))
    {
      $.ajax({
        url: '/_ajax/',
        data: { cl:'<?= $this->getClassName() ?>', tm:'<?= $this->tm_id ?>', a:'edit_delete', uid:uid },
        type: "POST",
        cache: false,
        success: function(data)
        {
          if (data=='ok')
            $('#user-list-edit-'+uid).remove();
          else
            alert('Ошибка!');
        },
        beforeSend: function() {}
      });
    }
    return false;
  }

  f_show_orders = function(uid)
  {
    $.ajax({
      url: '/_ajax/',
      data: { d:'edit_block', cl:'<?= $this->getClassName() ?>', tm:'<?= $this->tm_id ?>', uid:uid },
      type: "POST",
      cache: false,
      success: function(data)
      {
        $('.admin-popup-window').html(data);
        showPopup();
        $('#p-orders-list-00').click();
      },
      beforeSend: function() {}
    });
    return false;
  }

  newStatusElementP = function(oid, st)
  {
    if (confirm('Вы уверены, что хотите перевести заказ в другой статус?'))
    {
      $.ajax({
        url: '/_ajax/',
        data: { d:'edit_block', cl:'Basket', tm:'catalog', item_id:oid, admin_action:'new_status', status:st, act:'profile' },
        type: "POST",
        cache: false,
        success: function(data)
        {
          if (data=='ok')
            alert('Статус изменен!');
          else
            alert('Ошибка изменения статуса!');
        },
        beforeSend: function(){}
      });
    }
    return false;
  }
});
</script>