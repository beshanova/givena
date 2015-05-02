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
        <li><a href="#tabs-1">Просмотр</a></li>
        <li><a href="#tabs-2">Настройки</a></li>
    </ul>

	<p class="clear"></p>
	<br>
    <div id="tabs-1">
		<div class="mailform-scroll">
						<b>Сообщения</b><br>
						<br>
						<? if (!empty($mess)) : ?>
							<table class="mailform-scroll-table" border="0" style="border-spacing: 0px;">
								<tr style="font-weight: bold;">
									<td><b>ID</b></td>
									<td><b>Дата</b></td>
								<? foreach ($fields as $f) : ?>
									<td><b><?= $f['title'] ?></b></td>
								<? endforeach ; ?>
								</tr>
							<? foreach ($mess as $m) : ?>
								<tr>
									<td><?= $m['module_mailform_item_id'] ?></td>
									<td><?= $m['module_mailform_item_date_update'] ?></td>
								<? foreach ($fields as $n=>$f) : ?>
									<td>
									<? if ($f['f_type']=='checkbox') : ?>
										<span class=<?= ($m[$n] ? '"admin-popup-checkbox-yes">Да' : '"admin-popup-checkbox-no">Нет') ?> </span>
									<? elseif ($f['f_type']=='file') : ?>
										<a href="<?= $m[$n] ?>" target="_blank">Открыть</a>
									<? else : ?>
										<?= $m[$n] ?>
									<? endif ; ?>
									&nbsp;</td>
								<? endforeach ; ?>
								</tr>
							<? endforeach ; ?>
							</table>
						<? else : ?>
							<p>Нет добавленных элементов!</p>
						<? endif; ?>
		</div>
    </div>
    <div id="tabs-2">
		<table border="0" cellspacing="0" cellpadding="5" width="500">
			<tr>
				<td width="30%">
					E-mail адрес, куда будут приходить письма с формы:
				</td>
				<td>
					<input type="text" name="email" value="<?= $form['module_mailform_email'] ?>" />
				</td>
			</tr>
			<tr>
				<td>
					Заголовок отправляемого письма:
				</td>
				<td>
					<input type="text" name="subject" value="<?= $form['module_mailform_subject'] ?>" />
				</td>
			</tr>
			<? if (sizeof($tpls_l)>0) : ?>
			<tr>
				<td>
					Шаблон:
				</td>
				<td>
					<select name="tpl">
            <? foreach ($tpls_l as $t) : ?>
              <option value="<?= $t['file'] ?>"<?= ($t['file']==$form['module_mailform_tpl'] ? ' selected' : '') ?>><?= $t['title'] ?></option>
            <? endforeach ; ?>
					</select>
				</td>
			</tr>
			<? else : ?>
				<input type="hidden" value="template.php" name="tpl" />
			<? endif ; ?>
			<tr>
				<td>
					Активность блока:
				</td>
				<td>
					<input type="checkbox" name="is_active" class="" value="1" <?= ($form['topics_module_is_active'] ? 'checked' : '') ?>>
				</td>
			</tr>
		</table>
    </div>
</div>
<br>
<input type="submit" value="Сохранить" class="admin-popup-window-button-save">
<input type="button" value="Закрыть" class="admin-popup-window-button-close" />

</form>
</div>