<div class="title-pop ui-widget-header">
	<div class="title-pop-name">Резервное копирование</div>
	<div class="title-pop-close"><img src="/admin/img/close.png" alt="" style="float: left;"><input type="button" value="Закрыть" class="admin-popup-window-button-close" /></div>
</div>

<script>
$(function() {
    $( "#tabs" ).tabs();
});

$(document).ready(function(){

  StartBackupSystem = function()
  {
    var met = false;
    var param = '';
    $('.setup-backup00').each(function(){
      if ($(this).attr('checked'))
      {
        if ($(this).val()=='f_file' || $(this).val()=='db_db')
          met = true;
        param += ':' + $(this).val();
      }
    });

    if (!met)
    {
      alert('Выберите архивировать файлы и/или БД');
    }
    else
    {
      $.ajax({
        url: '/_ajax/',
        data: { cl:'_Backup', tm:0, admin_action:'start_backup', typeb:param },
        type: "POST",
        cache: false,
        success: function(data)
        {
          $('#tabs-2').html(data);
        },
        beforeSend: function()
        {
          $('#tabs-2').html('<img src="/admin/img/load.gif" border="0" id="preload-img" />');
        }
      });
    }
  }

  deleteDumpFile = function(fname, k)
  {
    $.ajax({
      url: '/_ajax/',
      data: { cl:'_Backup', tm:0, admin_action:'delete_backup', fname:fname },
      type: "POST",
      cache: false,
      success: function(data)
      {
        if (data=='ok')
          $('#dump-file'+k).remove();
        else
          alert('Ошибка удаления файла!');
      },
      beforeSend: function() {}
    });
  }

});
</script>

<div class="admin-popup-window-form">
  <div id="tabs">
    <ul>
        <li><a href="#tabs-1">Архивы</a></li>
        <li><a href="#tabs-2">Запуск</a></li>
    </ul>

    <p class="clear"></p>
  	<br>

    <div id="tabs-1">
    <? if (sizeof($files)>0) : ?>
      <table border="0" class="block_backup">
        <tr style="font-weight: bold;">
			<td>Имя файла</td> 
			<td>Размер, Мб</td> 
			<td>Удаление</td>
		</tr>
      <? foreach ($files as $k=>$f) : ?>
        <tr id="dump-file<?= $k ?>">
			<td>
				<a href="/files/backup/<?= $f ?>" title="Скачать"><?= $f ?></a>
			</td> 
			<td>
				<?= round(filesize(S_ROOT . '/files/backup/' . $f)/(1024*1024), 2) ?>
			</td> 
			<td class="del">
				<div class="menu_edit_delete" onclick="deleteDumpFile('<?= $f ?>', <?= $k ?>);"></div>
				<!--img src="/admin/img/del.gif" border="0" onclick="deleteDumpFile('<?= $f ?>', <?= $k ?>);" /-->
			</td>
		</tr>
      <? endforeach ; ?>
      </table>
    <? else : ?>
      <p>Нет созданных архивов!</p>
    <? endif ; ?>
	<br />
    </div>

    <div id="tabs-2">
		<table cellspacing="0" cellpadding="5" border="0">
			<tr>
				<td>
					Архивировать файлы:
				</td>
				
				<td>
					<input type="checkbox" class="setup-backup00" value="f_file" checked />
					<!--input type="checkbox" class="setup-backup00" value="f_upload" /> - исключить пользовательские файлы?-->
				</td>
			</tr>
			<tr>
				<td>
					Архивировать БД:
				</td>
				
				<td>
					<input type="checkbox" class="setup-backup00" value="db_db" checked />
        <!--input type="checkbox" class="setup-backup00" value="db_stat" /> - исключить статистику?-->
				</td>
			</tr>
		</table>
      
      <br>
	  <input type="button" value="Создать архив" class="admin-popup-window-button-save" onclick="StartBackupSystem();">
    </div>

  </div>
</div>
