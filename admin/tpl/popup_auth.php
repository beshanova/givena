<style>
.popup_auth {
    background: none repeat scroll 0 0 #FFFFFF;
    color: #000000;
    font-size: 12px;
    height: 225px;
    left: 40%;
    position: fixed;
    top: 200px;
    width: 295px;
    z-index: 100000;
}
.title-pop_auth {
	text-align: center;
	height:46px;
	background: #980b17 url(/admin/img/head-login.png);
}
.popup_auth_window_button_save {
	background-color: #980B17!important;
	height: 24px;
	background: url('/admin/img/black-white.png') right bottom repeat-x;
	border: 0;
	cursor: pointer;
	color: white;
	padding: 0px 15px 0px 15px;
	line-height: 22px;
	font-family: arial,sans-serif !important;
	font-size: 12px !important;
	margin: 7px 0px 0px 54px;
}
.popup_auth_window_button_save:hover {
	background: url('/admin/img/black-white-yellow.png') right top repeat-x;}
.popup_auth_window_button_close {
	background-color: #980B17!important;
	height: 24px;
	background: url('/admin/img/black-white.png') right bottom repeat-x;
	border: 0;
	cursor: pointer;
	color: white;
	padding: 0px 15px 0px 15px;
	line-height: 22px;
	font-family: arial,sans-serif !important;
	font-size: 12px !important;
}
.popup_auth_window_button_close:hover {
	background: url('/admin/img/black-white-yellow.png') right top repeat-x;}
.form_auth {padding: 20px 0px 0px 20px;}
a {color:#980b17;}
</style>
<script type="text/javascript" >
$(document).ready(
function() {
$('.form_auth input:first').focus();
});
</script>

<link href="/admin/css/popup.css" rel="stylesheet" type="text/css">
<script src="/admin/js/popup_dop.js" type="text/javascript"></script>
<div class="admin-popup-background"></div>
<script>$(document).ready(function(){showBG(1);});</script>

<div class="popup_auth" id="login-auth-form-01" onLoad="document.getElementById('login').focus()">
<div class="title-pop_auth">
	<img src="/admin/img/logo_login.png" alt="" style="">
</div>
  <p id="auth-error-00" style="display:none;margin:5px 0 -15px 0; text-align:center; color:red;">Логин и пароль введены не верно</p>
  <form action="" method="post" id="auth-form-00">
  <input type="hidden" name="admin_action" value="login_auth">
	<table class="form_auth">
		<tr>
			<td>
				<b>Логин:</b>
			</td>
			<td>
				<input type="text" name="user_login" id="login" />
			</td>
		</tr>
		<tr>
			<td>
				<b>Пароль:</b>
			</td>
			<td>
				<input type="password" name="user_pwd" id="pwd" />
			</td>
		</tr>
		<tr>
			<td>
				<b>Запомнить:</b>
			</td>
			<td>
				<input type="checkbox" name="is_remember" value="1" />
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="submit" class="popup_auth_window_button_save" value="Войти" onclick="return f_actionLogin();">&nbsp;&nbsp;<input type="button" class="popup_auth_window_button_close" value="Закрыть" onclick="$('#login-auth-form-01').remove(); closePopup();">
			</td>
		</tr>
	</table>
  </form>
	<span style="float: left; margin-left: 20px; margin-top: 10px;"><a href="http://www.editinplace.ru/" class="">www.editinplace.ru</a></span>
	<span style="float: right; margin-right: 20px; margin-top: 10px;">version 0.9 Beta</span>
</div>

<script>
function f_actionLogin()
{
  $('#auth-error-00').hide();
  $.ajax({
    url: '/_ajax/',
    data: { cl:'_User', tm:0, admin_action:'auth_test', l:$('#login').val(), p:$('#pwd').val() },
    type: "POST",
    cache: false,
    success: function(data)
    {
      if (data=='ok')
        $('#auth-form-00').submit();
      else
      {
        $('#auth-error-00').show(100);
        $('#pwd').val('').focus();
      }
    },
    beforeSend: function() {}
  });
  return false;
}
</script>