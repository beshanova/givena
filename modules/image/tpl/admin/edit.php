<script>
    $(function() {
        $( "#tabs" ).tabs();
    });
</script>

<div class="title-pop ui-widget-header">
	<div class="title-pop-name"><?= $this->getClassTitle() ?> / ID: <?= $this->tm_id ?></div>
	<div class="title-pop-close"><img src="/admin/img/close.png" alt="" style="float: left;"><input type="button" value="Закрыть" class="admin-popup-window-button-close" /></div>
</div>
<div class="admin-popup-window-form">
<form action="" method="post" enctype="multipart/form-data">
<input type="hidden" name="admin_action" value="save">
<input type="hidden" name="ajax" value="1">
<input type="hidden" name="cl" value="<?= $cl ?>">
<input type="hidden" name="tm" value="<?= $tm_id ?>">
<div id="tabs">
    <ul>
        <li><a href="#tabs-1">Добавить</a></li>
        <li><a href="#tabs-2">Настройки</a></li>
		<li><a href="#tabs-3">Справка</a></li>
    </ul>
	<p class="clear"></p>
	<br>
    <div id="tabs-1">
		<table border="0" cellspacing="0" cellpadding="5" width="500">
			<tr>
				<td class="pop-name-td" width="30%">
					Название:
				</td>
				<td>
					<input type="text" name="image[title]" value="<?= htmlspecialchars($image['module_image_title']) ?>" />
				</td>
			</tr>
		    <tr>
				<td class="pop-name-td">
					Ссылка:
				</td>
				<td>
					<input type="text" name="image[link]" value="<?= htmlspecialchars($image['module_image_link']) ?>" />
				</td>
			</tr>
			 <tr>
				<td class="pop-name-td">
					Позиционирование:
				</td>
				<td>
					<select name="image[target]">
					<option value="left"<?= ($image['module_image_target']=='left' ? ' selected' : '') ?>>Cлева</option>
					<option value="center"<?= ($image['module_image_target']=='center' ? ' selected' : '') ?>>По центру</option>
					<option value="right"<?= ($image['module_image_target']=='right' ? ' selected' : '') ?>>Справа</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="pop-name-td">
					Файл:
				</td>
				<td>
				  <input type="file" name="file" value="">
				  <? if ($image['module_image_src']) : ?>
				  <br>
				  [<a href="<?= $image['module_image_src'] ?>" target="_blank"><?= $image['module_image_src'] ?></a>]
				  <? endif ; ?>
			   </td>
			</tr>
			<tr>
				<td class="pop-name-td">
					Всплывать?
				</td>
				<td><input type="checkbox" name="image[is_popup]" value="1" <?= ($image['module_image_is_popup'] ? 'checked' : '') ?>></td>
			</tr>
		</table>
    </div>
    <div id="tabs-2">
		<table border="0" cellspacing="0" cellpadding="5" width="500">

			<? if (sizeof($tpls)>0) : ?>
			<tr>
				<td width="30%">
					Вид страницы:
				</td>
				<td>
					<select name="tpl">
					<? foreach ($tpls as $t) : ?>
						<option value="<?= $t['file'] ?>"<?= ($t['file']==$image['module_image_tpl'] ? ' selected' : '') ?>><?= $t['title'] ?></option>
					<? endforeach ; ?>
					</select>
				</td>
			</tr>
			<? else : ?>
				<input type="hidden" value="template.php" name="tpl" />
			<? endif ; ?>

			<tr>
				<td>
					Видимость блока:
				</td>
				<td>
					<input type="checkbox" name="is_active" value="1" <?= ($image['topics_module_is_active'] ? 'checked' : '') ?>>
				</td>
			</tr>
		</table>
    </div>
	<div id="tabs-3">
          <?= $APP->main_c_getHelpText($cl); ?>
    </div>
</div>
<br>
<input type="submit" value="Сохранить" class="admin-popup-window-button-save">
<input type="button" value="Закрыть" class="admin-popup-window-button-close" />

</form>
</div>