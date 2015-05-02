<div class="title-pop ui-widget-header">
	<div class="title-pop-name"><?= $this->getClassTitle() ?> / ID: <?= $this->tm_id ?></div>
	<div class="title-pop-close"><img src="/admin/img/close.png" alt="" style="float: left;"><input type="button" value="Закрыть" class="admin-popup-window-button-close" /></div>
</div>
<script>
    $(function() {
        $( "#tabs" ).tabs();
    });
    </script>

<div class="admin-popup-window-form">
<form action="" method="post">
<input type="hidden" name="admin_action" value="save">
<input type="hidden" name="ajax" value="1">
<input type="hidden" name="cl" value="<?= $cl ?>">
<input type="hidden" name="tm" value="<?= $tm_id ?>">
<div id="tabs">
    <ul>
        <li><a href="#tabs-1">Редактирование</a></li>
        <li><a href="#tabs-2">Настройки</a></li>
		<li><a href="#tabs-3">Справка</a></li>
    </ul>
	<p class="clear"></p>
	<br>
    <div id="tabs-1">
        <p>
			<textarea id="content-01" name="content" style="height:200px;"><?= $content['module_content_text'] ?></textarea>

			<? if (sizeof($tpls)>0) : ?>
				<table>
					<tr>
						<td>Вид:</td>
						<td>
							<select name="template">
							<? foreach ($tpls as $t) : ?>
								<option value="<?= $t['file'] ?>"<?= ($t['file']==$content['module_content_tpl'] ? ' selected' : '') ?>><?= $t['title'] ?></option>
							<? endforeach ; ?>
							</select>
						</td>
					</tr>
				</table>
			<? else : ?>
				<input type="hidden" value="<?= $content['module_content_tpl'] ?>" name="template" />
			<? endif ; ?>
			</p>
    </div>
    <div id="tabs-2">
		<table border="0" cellspacing="0" cellpadding="5">
			<tr>
				<td>Видимость блока:</td>
				<td>
					<input type="checkbox" name="is_active" value="1" <?= ($content['topics_module_is_active'] ? 'checked' : '') ?>>
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