<div class="title-pop ui-widget-header">
	<div class="title-pop-name"><?= $this->getClassTitle() ?> / ID: <?= $this->tm_id ?> (Изменен: <?= dateFormat($search['module_search_date_update'], true) ?>)</div>
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
        <li><a href="#tabs-1">Настройки</a></li>
        <li><a href="#tabs-2">Справка</a></li>
    </ul>
	<p class="clear"></p>
	<br>

    <div id="tabs-1">
		<table>
      <tr>
				<td>
					Символов до и после найденного:
				</td>
				<td>
					<input type="text" name="cnt_lit" value="<?= $search['module_search_cnt_literals'] ?>" />
				</td>
			</tr>
      <tr>
				<td>
					Результатов поиска на странице:
				</td>
				<td>
					<input type="text" name="cnt" value="<?= $search['module_search_cnt_results'] ?>" />
				</td>
			</tr>
		</table>
    </div>

    <div id="tabs-2">
  		<p><?= $APP->main_c_getHelpText($cl); ?></p>
    </div>
</div>
<br>
<input type="submit" value="Сохранить" class="admin-popup-window-button-save">
<input type="button" value="Закрыть" class="admin-popup-window-button-close" />

</form>
</div>