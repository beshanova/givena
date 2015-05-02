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
<input type="button" value="Закрыть" class="admin-popup-window-button-close" />

</form>
</div>