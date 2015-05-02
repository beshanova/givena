<div class="title-pop ui-widget-header">
	<div class="title-pop-name"><?= $this->getClassTitle() ?> / ID: <?= $this->tm_id ?> (Изменен: <?= dateFormat($title['module_title_date_update'], true) ?>)</div>
	<div class="title-pop-close"><img src="/admin/img/close.png" alt="" style="float: left;"><input type="button" value="Закрыть" class="admin-popup-window-button-close" /></div>
</div>

<div class="admin-popup-window-form">
<form action="" method="post">
<input type="hidden" name="admin_action" value="save">
<input type="hidden" name="ajax" value="1">
<input type="hidden" name="cl" value="<?= $cl ?>">
<input type="hidden" name="tm" value="<?= $tm_id ?>">
<script>
    $(function() {
        $( "#tabs" ).tabs();
    });
</script>
<div id="tabs">
    <ul>
        <li><a href="#tabs-1">Редактирование</a></li>
        <li><a href="#tabs-2">Настройки</a></li>
        <li><a href="#tabs-3">Справка</a></li>
    </ul>
	<p class="clear"></p>
	<br>
    <div id="tabs-1">
		<table border="0" cellspacing="0" cellpadding="5" width="500">
			<tr>
				<td width="30%">
					Заголовок:
				</td>
				<td>
					<input type="text" name="title" value="<?= htmlspecialchars($title['module_title_text']) ?>" size="50">
				</td>
			</tr>
			<? if (sizeof($tpls)>0) : ?>
			<tr>
				<td>
					Вид блока:
				</td>
				<td>
					<select name="template">
					<? foreach ($tpls as $t) : ?>
						<option value="<?= $t['file'] ?>"<?= ($t['file']==$title['module_title_tpl'] ? ' selected' : '') ?>><?= $t['title'] ?></option>
					<? endforeach ; ?>
					</select>
				</td>
			</tr>
			<? else : ?>
					<input type="hidden" value="template.php" name="template" />
				<? endif ; ?>
		</table>

    </div>

    <div id="tabs-2">
		<table border="0" cellspacing="0" cellpadding="5" width="500">
			<tr>
				<td width="30%">
					Видимость блока:
				</td>
				<td>
					<input type="checkbox" name="is_active" value="1" <?= ($title['topics_module_is_active'] ? 'checked' : '') ?>>
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