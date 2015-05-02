<div class="title-pop ui-widget-header">
	<div class="title-pop-name">Рассылка</div>
	<div class="title-pop-close"><img src="/admin/img/close.png" alt="" style="float: left;"><input type="button" value="Закрыть" class="admin-popup-window-button-close" /></div>
</div>

<script>
if (typeof CKEDITOR !=="undefined")
{
  if (CKEDITOR.instances['content-10']) { delete CKEDITOR.instances['content-10'] };
  if (CKEDITOR.instances['content-10']) { CKEDITOR.instances['content-10'].destroy(); }
}

$(document).ready(function(){
  $(function() {
    $( "#tabs" ).tabs();
  });

  SendSenmail = function(id)
  {
    $.ajax({
      url: '/_ajax/',
      data: { cl:'_Sendmail', tm:'<?= $data['sendmail_data_type'] ?>', admin_action:'send_sendmail', id:id },
      type: "POST",
      cache: false,
      success: function(data)
      {
        if (data)
        {
          $.post("/_ajax/", { cl:'_Sendmail', tm:'<?= $data['sendmail_data_type'] ?>', admin_action:'send_sendmail_go', id:id } );
          $('div.admin-popup-window').html(data);
        }
        else
          $('#status-mess-00').html('Ошибка рассылки!');
      },
      beforeSend: function() { $('#tabs-1').html('<img src="/admin/img/load.gif" border="0" id="preload-img" />'); }
    });
  }

  SendTestSenmail = function(id)
  {
    $.ajax({
      url: '/_ajax/',
      data: { cl:'_Sendmail', tm:'<?= $data['sendmail_data_type'] ?>', admin_action:'test_sendmail', id:id },
      type: "POST",
      cache: false,
      success: function(data)
      {
        $('#status-mess-00').show();
        if (data=='ok')
          $('#status-mess-00').html('Письмо отправлено, проверьте почту!');
        else
          $('#status-mess-00').html('Письмо не отправлено, ошибка!');
      },
      beforeSend: function() { $('#status-mess-00').html('Идет тестовая отправка: <img src="/admin/img/load.gif" border="0" id="preload-img" />').show(); }
    });
  }

  CopySenmail = function(id)
  {
    $.ajax({
      url: '/_ajax/',
      data: { cl:'_Sendmail', tm:'<?= $data['sendmail_data_type'] ?>', admin_action:'copy_sendmail', id:id },
      type: "POST",
      cache: false,
      success: function(data)
      {
        if (data)
          $('div.admin-popup-window').html(data);
        else
          $('#status-mess-00').html('Ошибка копирования записи!');
      },
      beforeSend: function() { $('#tabs-1').html('<img src="/admin/img/load.gif" border="0" id="preload-img" />'); }
    });
  }

  DeleteUser = function(id)
  {
    $.ajax({
      url: '/_ajax/',
      data: { cl:'_Sendmail', tm:'<?= $data['sendmail_data_type'] ?>', admin_action:'delete_muser', id:id },
      type: "POST",
      cache: false,
      success: function(data)
      {
        if (data=='ok')
          $('#musers-'+id).remove();
        else
          $('#status-mess-00').html('Ошибка удаления записи!');
      },
      beforeSend: function() {}
    });
  }

  DeleteSenmail = function(id)
  {
    $.ajax({
      url: '/_ajax/',
      data: { cl:'_Sendmail', tm:'<?= $data['sendmail_data_type'] ?>', admin_action:'delete_sendmail', id:id },
      type: "POST",
      cache: false,
      success: function(data)
      {
        if (data=='ok')
          $('#mlist-'+id).remove();
        else
          $('#status-mess-00').html('Ошибка удаления записи!');
      },
      beforeSend: function() {}
    });
  }

});
</script>

<div class="admin-popup-window-form">
  <div id="tabs">
    <ul>
        <li><a href="#tabs-1">Список писем</a></li>
        <li><a href="#tabs-2">Подписчики</a></li>
        <li><a href="#tabs-3">Настройки</a></li>
        <li><a href="#tabs-4">Создать рассылку</a></li>
    </ul>

    <p class="clear"></p>
  	<br>

    <div id="tabs-1">
    <? if (sizeof($mlist)>0) : ?>
      <div id="status-mess-00" style="display:none;"></div>
      <table border="0" class="block_backup" id="block-sendmail-00">
      <tr style="font-weight: bold;">
        <td>Дата</td>
        <td>Тема</td>
        <td>Статус</td>
        <td>Операции</td>
      </tr>
      <? foreach ($mlist as $k=>$f) : ?>
      <tr id="mlist-<?= $f['sendmail_list_id'] ?>">
        <td><?= date('d.m.Y H:i', strtotime($f['sendmail_list_date_add'])) ?></td>
        <td><?= $f['sendmail_list_title'] ?></td>
        <td><?= $this->t_getStatusText($f['sendmail_list_status']) ?> <?= (strtotime($f['sendmail_list_date_send'])>0?'<br>'.date('d.m.Y H:i', strtotime($f['sendmail_list_date_send'])):'')?></td>
        <td>
          <? if ($f['sendmail_list_status']==0) : ?>
          <a href="javascript://" onclick="return SendTestSenmail(<?= $f['sendmail_list_id'] ?>);">протестировать</a>
          /
          <a href="javascript://" onclick="return SendSenmail(<?= $f['sendmail_list_id'] ?>);">разослать</a>
          /
          <? endif ; ?>

          <a href="javascript://" onclick="return CopySenmail(<?= $f['sendmail_list_id'] ?>);">скопировать</a>
          /
          <? if ($f['sendmail_list_status']!=1) : ?>
          <a href="javascript://" onclick="return DeleteSenmail(<?= $f['sendmail_list_id'] ?>);">удалить</a>
          <? endif ; ?>
        </td>
		  </tr>
      <? endforeach ; ?>
      </table>
    <? else : ?>
      <p>Нет созданных рассылок!</p>
    <? endif ; ?>
	    <br />
    </div>

    <div id="tabs-2">
      <b>Добавление новых подписчиков:</b>
      <form action="" method="post">
        <input type="hidden" name="admin_action" value="save_musers">
        <input type="hidden" name="ajax" value="1">
        <input type="hidden" name="cl" value="_sendmail">
        <input type="hidden" name="tm" value="<?= $data['sendmail_data_type'] ?>">

        <p>Формат ввода: Email::ФИО (каждый подписчик с новой строки)</p>
        <textarea name="emails"></textarea>

        <input type="submit" value="Сохранить" class="admin-popup-window-button-save">
      </form>

    <? if (sizeof($musers)>0) : ?>
      <table border="0" class="block_backup">
      <tr style="font-weight: bold;">
        <td>E-mail</td>
        <td>Имя</td>
        <td>Удаление</td>
      </tr>
      <? foreach ($musers as $k=>$f) : ?>
      <tr id="musers-<?= $f['sendmail_user_id'] ?>">
        <td><?= $f['sendmail_user_email'] ?></td>
        <td><?= $f['sendmail_user_name'] ?></td>
        <td><a href="javascript://" onclick="return DeleteUser(<?= $f['sendmail_user_id'] ?>);">удалить</a></td>
		  </tr>
      <? endforeach ; ?>
      </table>
    <? else : ?>
      <p>Нет подписчиков!</p>
    <? endif ; ?>
	    <br />
    </div>

    <div id="tabs-3">
      <form action="" method="post">
        <input type="hidden" name="admin_action" value="save_data">
        <input type="hidden" name="ajax" value="1">
        <input type="hidden" name="cl" value="_sendmail">
        <input type="hidden" name="tm" value="<?= $data['sendmail_data_type'] ?>">
        <table cellspacing="0" cellpadding="5" border="0">
          <tr>
            <td>Тестовый E-mail:</td>
            <td><input type="text" name="data[email_test]" value="<?= htmlspecialchars($data['sendmail_data_email_test']) ?>"></td>
          </tr>
          <tr>
            <td>E-mail рассылки:</td>
            <td><input type="text" name="data[email_from]" value="<?= htmlspecialchars($data['sendmail_data_email_from']) ?>"></td>
          </tr>
        </table>

        <br>
        <input type="submit" value="Сохранить" class="admin-popup-window-button-save">
      </form>
    </div>

    <div id="tabs-4">
      <form action="" method="post">
        <input type="hidden" name="admin_action" value="save_sendmail">
        <input type="hidden" name="ajax" value="1">
        <input type="hidden" name="cl" value="_sendmail">
        <input type="hidden" name="tm" value="<?= $data['sendmail_data_type'] ?>">

        <table cellspacing="0" cellpadding="5" border="0">
          <tr>
            <td>Тема:</td>
            <td><input type="text" name="data[title]"></td>
          </tr>
          <tr>
            <td colspan="2">
              Сообщение:<br>
              <textarea id="content-10" name="data[text]" style="height:200px;"></textarea>
              <?php
              include_once(S_ROOT . '/admin/utils/ckeditor/ckeditor.php');
              $CK = new CKEditor('/admin/utils/ckeditor/');
              print $CK->replace('content-10');
              ?>
            </td>
          </tr>
        </table>

        <br>
        <input type="submit" value="Сохранить" class="admin-popup-window-button-save">
      </form>
    </div>

  </div>
</div>
