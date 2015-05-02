
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
	<ul>
    <li><a href="#tabs-1">Редактирование</a></li>
    <li id="wisiwig-01" style="display:none;"><a href="#tabs-4">Полное описание</a></li>
    <li><a href="#tabs-5">Разделы</a></li>
  </ul>
	<p class="clear"></p>
	<br>
    <div id="tabs-1">
	  <div class="edit_scroll">
		<table border="0" cellspacing="0" cellpadding="5" width="600">
			<?$wisiwig=0; foreach ($fields as $f) : ?>
      <?if ($f['f_type']=='wisiwig') { $wisiwig=1; continue; }?>
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

    <?if ($wisiwig):?>
    <div id="tabs-4">
	<div class="edit_scroll">
    <table border="0" cellspacing="0" cellpadding="5" width="600">
	
		<?foreach ($fields as $f) : ?>
		  <?if ($f['f_type']=='wisiwig'):?>
		  <script>
		  if (typeof CKEDITOR !=="undefined")
		  {
			if (CKEDITOR.instances['f-<?=$f['COLUMN_NAME']?>']) { delete CKEDITOR.instances['f-<?=$f['COLUMN_NAME']?>'] };
			if (CKEDITOR.instances['f-<?=$f['COLUMN_NAME']?>']) { CKEDITOR.instances['f-<?=$f['COLUMN_NAME']?>'].destroy(); }
		  }
		  </script>
		  <tr>
					<td class="pop-name-td">
						<?= $f['title'] ?>:<br>
						<? if (is_array($f['field'])) : ?>
							<?= implode('<br />', $f['field']) ?>
						<? else : ?>
							<?= $f['field'] ?>
						<? endif ; ?>

			  <?php
			  include_once(S_ROOT . '/admin/utils/ckeditor/ckeditor.php');
			  $CK = new CKEditor('/admin/utils/ckeditor/');
			  print $CK->replace('f-'.$f['COLUMN_NAME']);
			  ?>
					</td>
				</tr>
		  <?endif;?>
		<?endforeach;?>
	
    </table>
	</div>
    </div>
    <script>$('#wisiwig-01').show();</script>
    <?endif;?>

    <div id="tabs-5">
	<div class="edit_scroll">
    <ul>
    <? foreach ($groups as $g) : ?>
        <li>
            <input type="checkbox" name="group[<?= $g['topics_module_id'] ?>]"<?= ($g['selected']?' checked':'') ?> value="1">
            <input type="hidden" name="group_h[]" value="<?= $g['topics_module_id'] ?>">
            <?= $g['module_menu_title'] ?>
        </li>
    <? endforeach ; ?>
    </ul>
    </div>
    </div>

</div>
<br>
<input type="submit" value="Сохранить" class="admin-popup-window-button-save" onclick="return testDataCatalog('edit');" />
<input type="button" value="Закрыть" class="admin-popup-window-button-close" />

</form>
</div>