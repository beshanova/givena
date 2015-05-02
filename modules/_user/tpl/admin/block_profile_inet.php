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
		<br />
        <input type="submit" value="Сохранить" class="admin-popup-window-button-save" />
        <input type="button" value="Закрыть" class="admin-popup-window-button-close" />

      </form>
    </div>

  <? if ($is_super) : ?>

    <div id="tabs-2">
		<table id="users-list-01">
			<tr>
        <td>ID</td>
        <td>Группа</td>
  			<td>Логин</td>
				<td>Пароль</td>
        <td>Активность</td>
        <td>Редактировать</td>
        <td>Дата последнего изменения</td>
        <td>Удалить</td>
			</tr>
      <? foreach ($users as $u) : ?>
      <tr id="tr-user-<?= $u['user_id'] ?>">
        <td><?= $u['user_id'] ?></td>
        <td><?= ($u['user_group']==1 ? 'Суперадмин' : 'Модератор') ?></td>
  			<td><?= $u['user_login'] ?></td>
				<td>*****</td>
        <td><?= ($u['user_active']?'Да':'Нет') ?></td>
        <td><a href="javascript://" onclick="return editUser(<?= $u['user_id'] ?>);">Ред.</a></td>
        <td><?= dateFormat($u['user_date_update'], true) ?></td>
        <td>
        <? if ($u['user_id']!=$_SESSION['_SITE_']['userdata']['user_id']) : ?>
          <a href="javascript://" onclick="return deleteUser(<?= $u['user_id'] ?>);">Х</a>
        <? else : ?>
          -
        <? endif ; ?>
        </td>
			</tr>
      <? endforeach ; ?>
		</table>

    <br />
    <a href="javascript://" style="border-bottom: 1px dashed; text-decoration:none;" onclick="return addUser();">Добавить пользователя</a>
    <div id="div-user-add" style="display:none;"></div>

	
	 <br /> <br />
	<table class="menu_edit">
		<tr style="font-weight: bold;">
			<td>Группа</td>
			<td>Логин</td>
			<td>Пароль</td>
			<td>Редактировать</td>
			<td>Блокировать</td>
			<td>Удалить</td>
		</tr>
		<tr class="menu_edit_hide_item">
			<td>
				<select>
				  <option>Суперадмин</option>
				  <option>Модератор</option>
				</select>
			</td>
			<td><input type="text" value="admin"></td>
			<td><input type="text" value="******"></td>
			<td>
				<div class="user_list_edit"></div>
			</td>
			<td>
				<div class="user_list_block"></div>
			</td>
			<td>
				<div class="user_list_delete_not_activ"></div>
			</td>
		</tr>
		<tr>
			<td>Модератор</td>
			<td><div class="tooltip">user<span>Дата последнего изменения</br>26.10.2012 13:34</span></div></td>
			<td>******</td>
			<td>
				<div class="user_list_edit"></div>
			</td>
			<td>
				<div class="user_list_block"></div>
			</td>
			<td>
				<div class="user_list_delete"></div>
			</td>
		</tr>
		<tr class="menu_edit_delete_item">
			<td>Модератор</td>
			<td><div class="tooltip">user<span>Дата последнего изменения</br>26.10.2012 13:34</span></div></td>
			<td>******</td>
			<td>
				<div class="user_list_edit"></div>
			</td>
			<td>
				<div class="user_list_block"></div>
			</td>
			<td>
				<div class="user_list_delete"></div>
			</td>
		</tr>
		<tr>
			<td>Модератор</td>
			<td><div class="tooltip">user<span>Дата последнего изменения</br>26.10.2012 13:34</span></div></td>
			<td>******</td>
			<td>
				<div class="user_list_edit"></div>
			</td>
			<td>
				<div class="user_list_block"></div>
			</td>
			<td>
				<div class="user_list_delete"></div>
			</td>
		</tr>
		<tr class="menu_edit_new_item">
			<td>
				<select>
				  <option>Суперадмин</option>
				  <option>Модератор</option>
				</select>
			</td>
			<td>
				<input type="text" value="" />
			</td>
			<td>
				<input type="text" value="" />
			</td>
			<td>
				<div class="user_list_edit_not_activ"></div>
			</td>
			<td>
				<div class="user_list_block"></div>
			</td>
			<td>
				<div class="user_list_delete"></div>
			</td>
		</tr>
	</table>
    <br/>
    <div class="user_list_user_add" >
	   <img src="/admin/img/add.png" alt="" style="float: left;"/>
	   <span style="float: left;margin: 0px 0px 0px 5px;">Добавить пользователя</span>

    </div>
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
<? if ($is_super) : ?>

<script>
$(document).ready(function(){
  $("#tabs").tabs();

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

  deleteUser = function(uid)
  {
    if (confirm('Точно удалить пользователя?'))
    {
      $.ajax({
        url: '/_ajax/',
        data: { cl:'_User', tm:0, admin_action:'del_profile', item_id:uid },
        type: "POST",
        cache: false,
        success: function(data)
        {
          $('#tr-user-'+uid).remove();
        },
        beforeSend: function() {}
      });
    }
		return false;
  }

  editUser = function(uid)
  {
    $.ajax({
      url: '/_ajax/',
      data: { cl:'_User', tm:0, admin_action:'edit_user', item_id:uid },
      type: "POST",
      cache: false,
      success: function(data)
      {
        $('#tr-user-'+uid).html(data);
      },
      beforeSend: function() {}
    });
		return false;
  }

  addUser = function()
  {
    $.ajax({
      url: '/_ajax/',
      data: { cl:'_User', tm:0, admin_action:'add_user' },
      type: "POST",
      cache: false,
      success: function(data)
      {
        $('#div-user-add').html(data).show();
      },
      beforeSend: function() {}
    });
		return false;
  }

  editUserSave = function(uid)
  {
    $.ajax({
      url: '/_ajax/',
      data: { cl:'_User', tm:0, admin_action:'edit_user_save', item_id:uid, g:$('#user-group-'+uid).val(), l:$('#user-login-'+uid).val(), l_h:$('#user-login-h-'+uid).val(), p:$('#user-pwd-'+uid).val(), a:($('#user-active-'+uid).attr('checked')?1:0) },
      type: "POST",
      cache: false,
      success: function(data)
      {
        if (data.indexOf('id="tr-user-')>0)
        {
          $('#users-list-01').append(data);
          $('#div-user-add').html("").hide();
        }
        else
          $('#tr-user-'+uid).html(data);
      },
      beforeSend: function() {}
    });
		return false;
  }

});
</script>

<? endif ; ?>