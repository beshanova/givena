<div class="title-pop ui-widget-header">
	<div class="title-pop-name">Администрирование</div>
	<div class="title-pop-close"><img src="/admin/img/close.png" alt="" style="float: left;"><input type="button" value="Закрыть" class="admin-popup-window-button-close" /></div>
</div>

<div class="admin-popup-window-form">

<div id="tabs">
    <ul>
        <li><a href="#tabs-1">Профиль</a></li>
        <? if ($is_super) : ?>
        <li><a href="#tabs-2">Список пользователей</a></li>
        <li><a href="#tabs-3">История изменений</a></li>
        <? endif ; ?>
    </ul>
	<p class="clear"></p>
	<br>
    <div id="tabs-1">
      <form action="" method="post">
        <input type="hidden" name="admin_action" value="save_profile" />
        <input type="hidden" name="cl" value="_User" />
        <input type="hidden" name="tm" value="0" />
        <input type="hidden" name="ajax" value="1" />

        <table style="border-spacing: 0px;">
          <tr>
            <td>
              Старый пароль:
            </td>
            <td>
              <input type="password" name="pwd_old" value="" size="20" />
            </td>
          </tr>
          <tr>
            <td colspan="2"></td>
          </tr>
          <tr>
            <td>
              Новый пароль:
            </td>
            <td>
              <input type="password" name="pwd_new1" value="" size="20" />
            </td>
          </tr>
          <tr>
            <td>
              Новый пароль (еще раз):
            </td>
            <td>
              <input type="password" name="pwd_new2" value="" size="20" />
            </td>
          </tr>
        </table>
        <br>
        <input type="submit" value="Сохранить" class="admin-popup-window-button-save" />
        <input type="button" value="Закрыть" class="admin-popup-window-button-close" />

      </form>
    </div>

  <? if ($is_super) : ?>

    <div id="tabs-2">

    <form action="" method="post">
    <input type="hidden" name="admin_action" value="edit_user_save" />
    <input type="hidden" name="cl" value="_User" />
    <input type="hidden" name="tm" value="0" />
    <input type="hidden" name="ajax" value="1" />

		<table id="users-list-01" class="menu_edit">
			<tr style="font-weight: bold;">
        <td style="width: 125px;">Группа</td>
  		<td style="width: 145px;">Логин</td>
		<td style="width: 165px;">Пароль</td>
        <td>Редактировать</td>
        <td>Блокировать</td>
        <td>Удалить</td>
			</tr>

      <? $glob_max_id=0;
      foreach ($users as $u) :
        $glob_max_id = max($glob_max_id, $u['user_id']);
      ?>
      <tr id="tr-user-<?= $u['user_id'] ?>" class="tr-user-00<?= (!$u['user_active']?' menu_edit_hide_item':'') ?>">
        <td><?= ($u['user_group']==1 ? 'Суперадмин' : 'Модератор') ?></td>
        <td>
          <div class="tooltip"><p><?= $u['user_login'] ?></p><span class="tooltip-span">Дата последнего изменения</br><?= dateFormat($u['user_date_update'], true) ?></span></div>
          <input type="hidden" value="<?= htmlspecialchars($u['user_login']) ?>" name="user[<?= $u['user_id'] ?>][login_hide]" />
        </td>
				<td>*****</td>
        <td><div class="user_list_edit" onclick="f_edit_user(<?= $u['user_id'] ?>);"></div></td>
        <td><div class="user_list_block" onclick="f_edit_active(<?= $u['user_id'] ?>);"></div><input type="hidden" name="user[<?= $u['user_id'] ?>][active]" value="<?= ($u['user_active']?1:0) ?>" id="user-edit-active-<?= $u['user_id'] ?>" /></td>
        <td>
        <? if ($u['user_id']!=$_SESSION['_SITE_']['userdata']['user_id']) : ?>
          <div class="user_list_delete tooltip" onclick="f_edit_delete(<?= $u['user_id'] ?>);"><span class="tooltip-span">Нажмите на иконку, чтобы </br> удалить</span></div>
          <input type="hidden" name="user[<?= $u['user_id'] ?>][delete]" value="0" id="user-edit-delete-<?= $u['user_id'] ?>" />
        <? else : ?>
          <div class="user_list_delete_not_activ"></div>
        <? endif ; ?>
        </td>
			</tr>
      <? endforeach ; ?>

      <tr style="display:none;" id="tr-user-xx">
        <td>
          <select id="user-group-xx" name="user[xx][group]">
            <option value="1">Суперадмин</option>
            <option value="2">Модератор</option>
          </select>
        </td>
        <td><input type="text" value="" name="user[xx][login]"></td>
        <td><input type="text" value="" name="user[xx][pwd]"></td>
        <td>
          <div class="user_list_edit"></div>
        </td>
        <td>
          <div class="user_list_block" onclick="f_edit_active(xx);"></div>
          <input type="hidden" name="user[xx][active]" value="1" id="user-edit-active-xx" />
        </td>
        <td>
          <div class="user_list_delete" onclick="f_edit_delete(xx);"></div>
          <input type="hidden" name="user[xx][delete]" value="0" id="user-edit-delete-xx" />
        </td>
      </tr>
		</table>
    <br/>
    <div class="user_list_user_add" onclick="f_add_new_user();">
	   <img src="/admin/img/add.png" alt="" style="float: left;"/>
	   <span style="float: left;margin: 0px 0px 0px 5px;">Добавить пользователя</span>
    </div>

    <br>
    <input type="submit" value="Сохранить" class="admin-popup-window-button-save" />
    <input type="button" value="Закрыть" class="admin-popup-window-button-close" />

    </form>

    <?/*?><a href="javascript://" onclick="return editUser(<?= $u['user_id'] ?>);">Ред.</a>
        <?= dateFormat($u['user_date_update'], true) ?>
        <a href="javascript://" onclick="return deleteUser(<?= $u['user_id'] ?>);">Х</a>
    <a href="javascript://" style="border-bottom: 1px dashed; text-decoration:none;" onclick="return addUser();">Добавить пользователя</a>
    <div id="div-user-add" style="display:none;"></div>
    <?*/?>

    </div>

    <div id="tabs-3">
      C <input type="text" class="admin-field-style-dtext" id="admin-dt-01" value="<?= $_SESSION['_SITE_']['_User']['dt1'] ?>" style="width:120px;"> по <input type="text" class="admin-field-style-dtext" id="admin-dt-02" value="<?= $_SESSION['_SITE_']['_User']['dt2'] ?>" style="width:120px;" />
      <input type="button" class="block_profile_stats_show" value="Показать" onclick="return showStatByFilter();" />

      <div id="div-user-stats">
    		<?= $stat_t ?>
      </div>
    </div>

  <? endif ; ?>

</div>

</div>


<script>
$(document).ready(function(){
  $("#tabs").tabs();
});
</script>
<script>
jQuery(".tooltip").hover(
    function ()
    {
      jQuery(this).find('.tooltip-span').fadeIn(1);
    },
    function ()
    {
      jQuery(this).find('.tooltip-span').fadeOut(1);
    }
	);
</script>

<? if ($is_super) : ?>

<script>
$(document).ready(function(){

  $( ".admin-field-style-dtext" ).datepicker({
    showOn: "button",
    buttonImage: "/admin/img/calendar.gif",
    buttonImageOnly: true
  });

  showStatByFilter = function(){
    $.ajax({
      url: '/_ajax/',
      data: { cl:'_User', tm:0, dt1:$('#admin-dt-01').val(), dt2:$('#admin-dt-02').val(), admin_action:'filter_stat' },
      type: "POST",
      cache: false,
      success: function(data)
      {
        $('#div-user-stats').html(data);
      },
      beforeSend: function() {$('#div-user-stats').html('Идет загрузка...');}
    });
  }

  var glob_max_id = <?= $glob_max_id ?>;
  f_add_new_user = function()
  {
    glob_max_id++;
    var tr_text = $('#tr-user-xx').html();
    $('tr.tr-user-00:last').after('<tr id="tr-user-'+glob_max_id+'" class="menu_edit_new_item tr-user-00">' + tr_text.replace(/xx/ig, glob_max_id) + '</tr>');
  }

  f_edit_user = function(uid)
  {
		var group = $('#tr-user-'+uid+' td:first').text();
    var login = $('#tr-user-'+uid+' td:eq(1) div p').text();
    if (login!='')
    {
      $('#tr-user-'+uid+' td:first').html('<select id="user-group-xx" name="user['+uid+'][group]"><option value="1"'+(group=="Суперадмин"?" selected":"")+'>Суперадмин</option><option value="2"'+(group=="Модератор"?" selected":"")+'>Модератор</option></select>');
      $('#tr-user-'+uid+' td:eq(1)').html('<input type="text" value="'+login+'" name="user['+uid+'][login]"><input type="hidden" value="'+login+'" name="user['+uid+'][login_hide]">');
      $('#tr-user-'+uid+' td:eq(2)').html('<input type="password" value="" name="user['+uid+'][pwd]">');
    }
  }

  f_edit_active = function(uid)
  {
    var act = parseInt($('#user-edit-active-'+uid).val(), 10);
    if (act==1)
    {
      $('#user-edit-active-'+uid).val(0);
      $('#tr-user-'+uid).addClass('menu_edit_hide_item');
    }
    else
    {
      $('#user-edit-active-'+uid).val(1);
      $('#tr-user-'+uid).removeClass('menu_edit_hide_item');
    }
  }

  f_edit_delete = function(uid)
  {
    var del = parseInt($('#user-edit-delete-'+uid).val(), 10);
    if (del==1)
    {
      $('#user-edit-delete-'+uid).val(0);
      $('#tr-user-'+uid).removeClass('menu_edit_delete_item');
    }
    else
    {
      $('#user-edit-delete-'+uid).val(1);
      $('#tr-user-'+uid).addClass('menu_edit_delete_item');
    }
  }

});
</script>

<? endif ; ?>