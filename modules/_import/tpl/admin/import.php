<div class="title-pop ui-widget-header">
	<div class="title-pop-name"><?= $this->getClassTitle() ?></div>
	<div class="title-pop-close"><img src="/admin/img/close.png" alt="" style="float: left;"><input type="button" value="Закрыть" class="admin-popup-window-button-close" /></div>
</div>

<script src="/admin/js/jquery-ui-ajaxupload.js" type="text/javascript"></script>

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

$(document).ready(function() {
  var button = $('#upload-button-01');
  $.ajax_upload(button, {
        action : '/_ajax/?cl=_Import&tm=0&admin_action=upload',
        name : 'csvfile',
        onSubmit : function(file, ext)
        {
          // Выключаем кнопку на время загрузки файла
          button.hide();
          $("#text-upload-01").text('Идет загрузка файла').show();
        },
        onComplete : function(file, response)
        {
          // файл загружен, выполняем необходимые действия
          if (response=='ok')
          {
			var v_del = ($('#v-del-01').attr('checked')=='checked') ? 1 : 0;
            $("#text-upload-01").append('<br>Файла загружен<br>Начинается обработка файла');
            $.post("/_ajax/", { cl:'_Import', tm:0, admin_action:'load', v_del:v_del } ).done( function(data) { $("#text-upload-01").append('<br>'+data) } );
          }
          else if (response=='err1')
            $("#text-upload-01").append('<br>'+response);
          else
            $("#text-upload-01").append('<br>'+response);

          button.show();
        }
  });
});
</script>
<div id="tabs">
    <ul>
        <li><a href="#tabs-1">Импорт товаров</a></li>
        <li><a href="#tabs-3">Справка</a></li>
    </ul>
	<p class="clear"></p>
	<br>
    <div id="tabs-1">
	  <input type="checkbox" name="v_del" value="1" id="v-del-01"> - удалять или нет товары каталога, которых нет в файле импорта (если галочка стоит, то удалять)
      <p>Для начала загрузки выберите csv файл на своем компьютере</p>
  		<input type="button" value="Выбрать файл" id="upload-button-01" class="admin-popup-window-button-close" />
      <p id="text-upload-01" style="display:none;"></p>
    </div>

    <div id="tabs-3">
  		<p><?= $APP->main_c_getHelpText($cl); ?></p>
    </div>
</div>
<br>
<input type="button" value="Закрыть" class="admin-popup-window-button-close" />

</form>
</div>