
<script src="/admin/js/test_data_catalog.js" type="text/javascript"></script>

<div class="title-pop ui-widget-header">
	<div class="title-pop-name"><?= $this->getClassTitle() ?> / ID_ELEM: <?= $this->item_id ?></div>
	<div class="title-pop-close"><img src="/admin/img/close.png" alt="" style="float: left;"><input type="button" value="Закрыть" class="admin-popup-window-button-close" /></div>
</div>
<div class="admin-popup-window-form">
<form action="" method="post" enctype="multipart/form-data">
<input type="hidden" name="admin_action" value="save_item">
<input type="hidden" name="ajax" value="1">
<input type="hidden" name="cl" value="<?= $cl ?>">
<input type="hidden" name="tm" value="<?= $tm_id ?>">
<input type="hidden" name="item_id" value="<?= $catalog['module_list_item_id'] ?>">

<div id="tabs">
	<ul><li><a href="#tabs-1">Редактирование</a></li></ul>
	<p class="clear"></p>
	<br>
    <div id="tabs-1">
		<table border="0" cellspacing="0" cellpadding="5" width="500">
			<?foreach ($fields as $f) : ?>
			<tr>
				<td class="pop-name-td" width="30%">
				<?= $f['title'] ?> :
				</td>
				<td>
					<? if (is_array($f['field'])) : ?>
						<?= implode('<br /><br />', $f['field']) ?>
					<? else : ?>
						<?= $f['field'] ?>
						<? if (preg_match('~\|file~i', $f['COLUMN_COMMENT']) && $f['value']!="") : ?>
						<br />
						[<a href="<?= $f['value'] ?>" target="_blank"><?= $f['value'] ?></a>]
						<? endif ; ?>
					<? endif ; ?>
					<br>
				</td>
			</tr>
			<? endforeach ; ?>
			<tr>
				<td class="pop-name-td" width="30%">Активность:</td>
				<td><input type="checkbox" name="catalog[module_list_item_is_active]" value="1" <?= ($catalog['module_list_item_is_active'] ? 'checked' : '') ?>></td>
			</tr>
		</table>
    </div>
</div>
<br>
<input type="submit" value="Сохранить" class="admin-popup-window-button-save" onclick="return testDataCatalog('edit');" />
<input type="button" value="Закрыть" class="admin-popup-window-button-close" />

</form>
</div>