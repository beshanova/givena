<div class="title-pop ui-widget-header">
	<div class="title-pop-name"><?= $this->getClassTitle() ?> / ID: <?= $this->tm_id ?></div>
	<div class="title-pop-close"><img src="/admin/img/close.png" alt="" style="float: left;"><input type="button" value="Закрыть" class="admin-popup-window-button-close" /></div>
</div>
<div class="admin-popup-window-form">
<form action="" method="post">
<input type="hidden" name="admin_action" value="save">
<input type="hidden" name="ajax" value="1">
<input type="hidden" name="cl" value="<?= $cl ?>">
<input type="hidden" name="tm" value="<?= $tm_id ?>">
<br>
<? if (sizeof($menu)>0) : ?>
    <table>
        <tr>
            <td>Родитель</td>
            <td>Название пункта</td>
            <td>Ссылка</td>
            <td>Порядок</td>
            <td>Скрыть</td>
            <td>Удалить</td>
        </tr>
    <? foreach ($menu as $m) : ?>
        <tr<?= ($m['module_menu_url_type']==1?' style="background:#ffcc66;"':'') ?>>
            <td>
                <select name="menu[<?= $m['module_menu_id'] ?>][parent_id]">
                    <option value="0">Верхний уровень</option>
                <? foreach ($menu as $m2) : ?>
                    <option value="<?= $m2['module_menu_id'] ?>"<?= ($m2['module_menu_id']==$m['module_menu_parent_id']?' selected':'') ?>><?= str_repeat('•', $m2['level']+1) ?>&nbsp;<?= $m2['module_menu_title'] ?></option>
                <? endforeach ; ?>
                </select>
            </td>
            <td><?= str_repeat('•', $m['level']+1) ?>&nbsp;<input type="text" name="menu[<?= $m['module_menu_id'] ?>][title]" value="<?= $m['module_menu_title'] ?>"></td>
            <td>
              <input type="text" name="menu[<?= $m['module_menu_id'] ?>][url]" value="<?= ($m['module_menu_url_type']!=2?'/':'') . $m['urls'] . ($m['module_menu_url']!=""&&$m['module_menu_url_type']!=2?'/':'') ?>">
              <input type="hidden" name="menu[<?= $m['module_menu_id'] ?>][url_parent_hidden]" value="<?= str_replace($m['module_menu_url'], '', $m['urls']) ?>">
            </td>
            <td><input type="text" name="menu[<?= $m['module_menu_id'] ?>][sort]" value="<?= $m['module_menu_sortby'] ?>" size="4"></td>
            <td><input type="checkbox" name="menu[<?= $m['module_menu_id'] ?>][hidden]" value="1"<?= ($m['module_menu_is_hidden']?' checked':'') ?>></td>
            <td><input type="checkbox" name="menu[<?= $m['module_menu_id'] ?>][delete]" value="1"></td>
        </tr>
    <? endforeach ; ?>
    </table>
<? endif ; ?>
    <br>
<table>
	<tr>
		<td>
			Родитель:
			<select name="menu_new[0][parent_id]">
				<option value="0">Верхний уровень</option>
			<? foreach ($menu as $m2) : ?>
				<option value="<?= $m2['module_menu_id'] ?>"<?= ($m2['module_menu_id']==$_SESSION['_SITE_']['topic'][0]['module_menu_id']?' selected':'') ?>><?= str_repeat('•', $m2['level']+1) ?>&nbsp;<?= $m2['module_menu_title'] ?></option>
			<? endforeach ; ?>
			</select>
		</td>
		<td>
			Новый пункт*: <input type="text" name="menu_new[0][title]" value="">
		</td>
		<td>
			Новая ссылка: <input type="text" name="menu_new[0][url]" value="">
		</td>
	</tr>
</table>
<br>
<input type="submit" value="Сохранить" class="admin-popup-window-button-save">
<input type="button" value="Отменить" class="admin-popup-window-button-close" />

</form>
<br><br><br>
<div>
<span class="ui-tabs-anchor-const">Редактирование навигации</span>
<br><br>
<table class="menu_edit">
	<!--tr class="menu_edit_top">
		<td style="width: 185px;"></td>
		<td style="width: 40px;"></td>
		<td style="width: 20px;"></td>
		<td style="width: 180px;"></td>
		<td style="width: 20px;"></td>
		<td style="width: 70px;"></td>
		<td style="width: 65px;"></td>
		<td style="width: 50px;"></td>
	</tr-->
	<tr class="menu_edit_name">
		<td>Название пункта</td>
		<td></td>
		<td></td>
		<td>Ссылка</td>
		<td></td>
		<td>Порядок</td>
		<td>Скрыть</td>
		<td>Удалить</td>
	</tr>
	<tr>
		<td>
			<a href="#" class="">Главная</a>
		</td>
		<td>
			<div class="menu_edit_edit tooltip"><span>Нажмите на иконку, чтобы </br> изменить название раздела</span></div>
		</td>
		<td> </td>
		<td><input type="text" value="/"></input></td>
		<td> </td>
		<td>
			<div class="menu_edit_sort_down"></div>
			<div class="menu_edit_sort_up_not_activ"></div>
			<div class="menu_edit_sort_dr_not_activ"></div>
		</td>
		<td>
			<div class="menu_edit_hide"></div>
		</td>
		<td>
			<div class="menu_edit_delete_not_activ"></div>
		</td>
	</tr>
	<tr>
		<td>
			<div class="menu_edit_open"></div>
			<a href="#" class="">Что еще сделать</a>
		</td>
		<td>
			<div class="menu_edit_edit tooltip"><span>Нажмите на иконку, чтобы </br> изменить название раздела</span></div>
		</td>
		<td> </td>
		<td><input type="text" value="/whattodo/"></td>
		<td> </td>
		<td>
			<div class="menu_edit_sort_down"></div>
			<div class="menu_edit_sort_up"></div>
			<div class="menu_edit_sort_dr"></div>
		</td>
		<td>
			<div class="menu_edit_hide"></div>
		</td>
		<td>
			<div class="menu_edit_delete"></div>
		</td>
	</tr>
	<tr>
		<td style="padding-left: 50px;">
			<a href="#" class="">ttt2</a>
		</td>
		<td>
			<div class="menu_edit_edit tooltip"><span>Нажмите на иконку, чтобы </br> изменить название раздела</span></div>
		</td>
		<td> </td>
		<td><input type="text" value="/whattodo/ttt2"></td>
		<td> </td>
		<td>
			<div class="menu_edit_sort_down"></div>
			<div class="menu_edit_sort_up"></div>
			<div class="menu_edit_sort_dr_not_activ"></div>
		</td>
		<td>
			<div class="menu_edit_hide"></div>
		</td>
		<td>
			<div class="menu_edit_delete"></div>
		</td>
	</tr>
	<tr class="menu_edit_link_item">
		<td style="padding-left: 30px;">
			<div class="menu_edit_close"></div>
			<a href="#" class="">ttt3</a>
		</td>
		<td>
			<div class="menu_edit_edit tooltip"><span>Нажмите на иконку, чтобы </br> изменить название раздела</span></div>
		</td>
		<td> </td>
		<td><input type="text" value="/whattodo/ttt3"></td>
		<td> </td>
		<td>
			<div class="menu_edit_sort_down"></div>
			<div class="menu_edit_sort_up"></div>
			<div class="menu_edit_sort_dr"></div>
		</td>
		<td>
			<div class="menu_edit_hide"></div>
		</td>
		<td>
			<div class="menu_edit_delete"></div>
		</td>
	</tr>
	<tr class="menu_edit_hide_item">
		<td style="padding-left: 30px;">
			<div class="menu_edit_open"></div>
			<input type="text" value="ttt4">
		</td>
		<td>
			<div class="menu_edit_edit tooltip"><span>Нажмите на иконку, чтобы </br> изменить название раздела</span></div>
		</td>
		<td> </td>
		<td><input type="text" value="/whattodo/ttt4"></td>
		<td> </td>
		<td>
			<div class="menu_edit_sort_down"></div>
			<div class="menu_edit_sort_up"></div>
			<div class="menu_edit_sort_dr"></div>
		</td>
		<td>
			<div class="menu_edit_hide"></div>
		</td>
		<td>
			<div class="menu_edit_delete"></div>
		</td>
	</tr>
	<tr class="menu_edit_delete_item">
		<td style="padding-left: 70px;">
			<a href="#" class="">ttt5</a>
		</td>
		<td>
			<div class="menu_edit_edit tooltip"><span>Нажмите на иконку, чтобы </br> изменить название раздела</span></div>
		</td>
		<td> </td>
		<td><input type="text" value="/whattodo/ttt5"></td>
		<td> </td>
		<td>
			<div class="menu_edit_sort_down"></div>
			<div class="menu_edit_sort_up"></div>
			<div class="menu_edit_sort_dr_not_activ"></div>
		</td>
		<td>
			<div class="menu_edit_hide"></div>
		</td>
		<td>
			<div class="menu_edit_delete"></div>
		</td>
	</tr>
	<tr>
		<td style="padding-left: 50px;">
			<div class="menu_edit_close"></div>
			<a href="#" class="">ttt6</a>
		</td>
		<td>
			<div class="menu_edit_edit tooltip"><span>Нажмите на иконку, чтобы </br> изменить название раздела</span></div>
		</td>
		<td> </td>
		<td><input type="text" value="/whattodo/ttt6"></td>
		<td> </td>
		<td>
			<div class="menu_edit_sort_down"></div>
			<div class="menu_edit_sort_up"></div>
			<div class="menu_edit_sort_dr"></div>
		</td>
		<td>
			<div class="menu_edit_hide"></div>
		</td>
		<td>
			<div class="menu_edit_delete"></div>
		</td>
	</tr>
	<tr>
		<td>
			<div class="menu_edit_close"></div>
			<a href="#" class="">Пресс-центр</a>
		</td>
		<td>
			<div class="menu_edit_edit tooltip"><span>Нажмите на иконку, чтобы </br> изменить название раздела</span></div>
		</td>
		<td> </td>
		<td><input type="text" value="/news/"></td>
		<td> </td>
		<td>
			<div class="menu_edit_sort_down"></div>
			<div class="menu_edit_sort_up"></div>
			<div class="menu_edit_sort_dr"></div>
		</td>
		<td>
			<div class="menu_edit_hide"></div>
		</td>
		<td>
			<div class="menu_edit_delete"></div>
		</td>
	</tr>
	<tr class="menu_edit_new_item">
		<td>
			<div class="menu_edit_new"></div>
			<input type="text" value="">
		</td>
		<td>
			<div class="menu_edit_edit tooltip"><span>Нажмите на иконку, чтобы </br> изменить название раздела</span></div>
		</td>
		<td> </td>
		<td><input type="text" value="/"></td>
		<td> </td>
		<td>
			<div class="menu_edit_sort_down_not_activ"></div>
			<div class="menu_edit_sort_up"></div>
			<div class="menu_edit_sort_dr"></div>
		</td>
		<td>
			<div class="menu_edit_hide"></div>
		</td>
		<td>
			<div class="menu_edit_delete"></div>
		</td>
	</tr>
</table>

</div>
<br><br>
<input type="submit" value="Добавить раздел" class="admin-popup-window-button-save">
<br><br><br><br>

</div>