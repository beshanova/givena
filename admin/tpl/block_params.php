<script>
$(document).ready(function(){
  $(function() {
      $( "#tabs" ).tabs();
  });

  deleteTopicImg = function()
  {
    $.post("/_ajax/", { cl:'_Main', admin_action:'delete_page_img' } );
    $('#params-page-img-01').remove();
  }
});
</script>

<div class="title-pop ui-widget-header">
	<div class="title-pop-name">Параметры страницы</div>
	<div class="title-pop-close"><img src="/admin/img/close.png" alt="" style="float: left;"><input type="button" value="Закрыть" class="admin-popup-window-button-close" /></div>
</div>
<div class="admin-popup-window-form">

<div id="tabs">
    <ul>
        <li><a href="#tabs-1">Параметры страницы</a></li>
        <li><a href="#tabs-2">Мета-теги</a></li>
    </ul>

    <p class="clear"></p>
  	<br>
<form action="" method="post" enctype="multipart/form-data">
	<input type="hidden" name="admin_action" value="save_params">
	<input type="hidden" name="ajax" value="1">
	<input type="hidden" name="cl" value="_Main">
    <div id="tabs-1">
			<table style="width: 470px; " cellpadding="5">
				<tr>
					<td>
						Название:
					</td>
					<td>
						<input type="text" name="page_name" value="<?= htmlspecialchars($_SESSION['_SITE_']['topic'][0]['page_name']) ?>">
					</td>
				</tr>
				<tr>
					<td>
						Описание:
					</td>
					<td>
						<textarea name="page_desc"><?= $_SESSION['_SITE_']['topic'][0]['page_desc'] ?></textarea>
					</td>
				</tr>
				<tr>
					<td>
						Картинка:
					</td>
					<td>
						<input type="file" name="page_img">
            <? if ($_SESSION['_SITE_']['topic'][0]['page_img']!="") : ?>
            <p id="params-page-img-01">
              <a href="<?= $_SESSION['_SITE_']['topic'][0]['page_img'] ?>" target="_blank" title="Откроется в новом окне"><?= $_SESSION['_SITE_']['topic'][0]['page_img'] ?></a>&nbsp;
              <a href="javascript://" onclick="return deleteTopicImg();"><img src="/admin/img/del.gif"></a>
            </p>
            <? endif ; ?>
					</td>
				</tr>
			</table>
    </div>

    <div id="tabs-2">
			<table style=" width: 470px; " cellpadding="5">
				<tr>
					<td>
						Meta title:
					</td>
					<td>
						<input type="text" name="meta_title" value="<?= htmlspecialchars($_SESSION['_SITE_']['topic'][0]['meta_title']) ?>">
					</td>
				</tr>
				<tr>
					<td>
						Meta keywords:
					</td>
					<td>
						<textarea name="meta_keywords"><?= $_SESSION['_SITE_']['topic'][0]['meta_keywords'] ?></textarea>
					</td>
				</tr>
				<tr>
					<td>
						Meta description:
					</td>
					<td>
						<textarea name="meta_desc"><?= $_SESSION['_SITE_']['topic'][0]['meta_desc'] ?></textarea>
					</td>
				</tr>
			</table>

    </div>
    <input type="submit" value="Сохранить" class="admin-popup-window-button-save">
	<input type="button" value="Закрыть" class="admin-popup-window-button-close" />
</form>

</div>


<!--span class="ui-tabs-anchor-const">Параметры страницы</span-->


</div>