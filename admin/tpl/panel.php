<div id="admin-div-panel-static">
  <div id="wrapper-top">
	<div class="admin-panel-logo" onClick="window.location='/'"></div>
	<div class="top-clear-left"></div>

	<div class="admin-panel-adm left">
		<img src="/admin/img/set.png" alt="" class="left">
		<span style="margin-left: 5px;">Администрирование</span>
		<img src="/admin/img/admin-drop.png" alt="" class="right">

		<div class="admin-panel-adm-hide">
			<div class="admin-panel-param" onclick="return loadPopup('params', $(this));" cl="_Main"><img src="/admin/img/admin-set.png" alt="" class="left"><span style="margin-left: 5px;">Параметры страницы</span></div>
			<div class="admin-panel-param" onclick="return loadPopup('fileman', $(this));" cl="_Main"><img src="/admin/img/admin-file.png" alt="" class="left" style="margin-top:4px;"><span style="margin-left: 5px;">Менеджер файлов</span></div>
			<div class="admin-panel-param" onclick="return loadPopup('about', $(this));" cl="_Main"><img src="/admin/img/admin-about.png" alt="" class="left" style="margin-top:4px;"><span style="margin-left: 5px;">О системе</span></div>
      <div class="admin-panel-param" onclick="return loadPopup('', $(this));" cl="_Backup" tm="0"><img src="/admin/img/admin-backup.png" alt="" class="left" style="margin-top:4px;"><span style="margin-left: 5px;">Резервное копирование</span></div>
      <div class="admin-panel-param" onclick="return loadPopup('', $(this));" cl="_Sendmail" tm="0"><img src="/admin/img/admin-backup.png" alt="" class="left" style="margin-top:4px;"><span style="margin-left: 5px;">Рассылка</span></div>
      <div class="admin-panel-param" onclick="return loadPopup('', $(this));" cl="_Import" tm="0"><img src="/admin/img/admin-backup.png" alt="" class="left" style="margin-top:4px;"><span style="margin-left: 5px;">Импорт товаров</span></div>
		</div>
	</div>

	<div class="top-clear-left"></div>

  <?$sign = preg_match('~\?~', $_SERVER['REQUEST_URI'])? '&' : '?'; ?>
  <div class="admin-panel-view">

  <? if ($_SESSION['_SITE_']['is_adm']==1) : ?>
    <div class="admin-panel-view-l-activ left">
      Редактирование
    </div>
  <? else : ?>
    <div class="admin-panel-view-l left">
      <a href="<?= $_SERVER['REQUEST_URI'].$sign ?>admin_action=change_mode&cl=_Main&ajax=1" title="Перейти в режим редактирования">Редактирование</a>
    </div>
  <?endif;?>

  <? if ($_SESSION['_SITE_']['is_adm']==2) : ?>
    <div class="admin-panel-view-r-activ left">
      Просмотр
    </div>
  <? else : ?>
    <div class="admin-panel-view-r left">
      <a href="<?= $_SERVER['REQUEST_URI'].$sign ?>admin_action=change_mode&cl=_Main&ajax=1" title="Перейти в режим просмотра">Просмотр</a>
    </div>
  <?endif;?>

  </div>

	<div class="admin-panel-user" onclick="return loadPopup('profile', $(this));" cl="_User" tm="0">
	  <div class="top-clear-left"></div>
		<img src="/admin/img/user_pic.png" alt="" style="border:0; float: left;">
		<span style="margin-left: 5px;">
		Профиль
		</span>
	</div>

	<div class="top-clear"></div>
	<div class="admin-panel-exit" оnclick="location.href='/admin/?logout';"><a href="/admin/?logout"><img src="/admin/img/exit.png" alt="" style="border:0; float: left;"><span style="margin-left: 5px;">Выход</span></a></div>
	<div class="top-clear"></div>
  </div>
</div>

<div class="admin-popup-background"></div>
<div id="draggable" class="admin-popup-window ui-widget-content"></div>


<div class="top-clear"></div>
