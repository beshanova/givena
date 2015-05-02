
<script src="/admin/js/test_data_catalog.js" type="text/javascript"></script>

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
        <li><a href="#tabs-1">Добавление</a></li>
        <li><a href="#tabs-2">Настройки</a></li>
		<li><a href="#tabs-3">Справка</a></li>
	</ul>
	<p class="clear"></p>
	<br>
    <div id="tabs-1">
		<table border="0" cellspacing="0" cellpadding="5" width=500>
			<? foreach ($fields as $f) : ?>
			<tr>
				<td class="pop-name-td" width="30%">
					<?= $f['title'] ?> :
				</td>
				<td>
					<? if (is_array($f['field'])) : ?>
						<?= implode('<br />', $f['field']) ?>
					<? else : ?>
						<?= $f['field'] ?>
					<? endif ; ?>
				</td>
			</tr>
			<? endforeach ; ?>

			<tr style="display:none;">
				<td>
					Сортировка:
				</td>
				<td>
					<input type="hidden" name="catalog[sortby]" value="" />
				</td>
			</tr>

			<tr>
				<td>
					Активность:
				</td>
				<td>
					<input type="checkbox" name="catalog[is_active]" value="1" checked />
				</td>
			</tr>
		</table>
    </div>
    <div id="tabs-2">
		<table border="0" cellspacing="0" cellpadding="5" width=500>
			<tr>
				<td class="pop-name-td" width="30%">Элементов на странице:</td>
				<td>
					<input type="text" name="count" value="<?= $catalog['module_list_count'] ?>" size="3" />
				</td>
			</tr>

			<? if (sizeof($tpls_l)>0) : ?>
			<tr>
				<td>Вид списка:</td>
				<td>
					<select name="template_list">
					<? foreach ($tpls_l as $t) : ?>
						<option value="<?= $t['file'] ?>"<?= ($t['file']==$catalog['module_list_list_tpl'] ? ' selected' : '') ?>><?= $t['title'] ?></option>
					<? endforeach ; ?>
					</select>
				</td>
			</tr>
			<? else : ?>
				<input type="hidden" value="list/template.php" name="template_list" />
			<? endif ; ?>

			<? if (sizeof($tpls_d)>0) : ?>
			<tr>
				<td>Вид детальный:</td>
				<td>
					<select name="template_detail">
					<? foreach ($tpls_d as $t) : ?>
						<option value="<?= $t['file'] ?>"<?= ($t['file']==$catalog['module_list_detail_tpl'] ? ' selected' : '') ?>><?= $t['title'] ?></option>
					<? endforeach ; ?>
					</select>
				</td>
			</tr>
			<? else : ?>
				<input type="hidden" value="detail/template.php" name="template_detail" />
			<? endif ; ?>

			<tr>
				<td>Видимость блока:</td>
				<td>
					<input type="checkbox" name="is_active" value="1" <?= ($catalog['topics_module_is_active'] ? 'checked' : '') ?>>
				</td>
			</tr>
		</table>
    </div>
	<div id="tabs-3">
          <p><?= $APP->main_с_getHelpText($cl); ?></p>
    </div>
</div>
<br>
<input type="submit" value="Сохранить" class="admin-popup-window-button-save" onclick="return testDataCatalog('add');" />
<input type="button" value="Закрыть" class="admin-popup-window-button-close" />

</form>
</div>