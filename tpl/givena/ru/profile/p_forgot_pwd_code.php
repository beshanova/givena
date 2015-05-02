<?#Страница изменения пароля после перехода по ссылке из письма#?>

<link type="text/css" rel="stylesheet" href="/tpl/<?= $_SESSION['_SITE_']['theme'] ?>/css/profile.css">

<h2>Изменение пароля</h2>
<div class="form-contacts forgot_pwd_code" >
<? if ($_SESSION['_SITE_']['profiledata']['message']['success']) : ?>
  <p class="success">Пароль успешно изменен!</p>
<? else : ?>

  <? if ($_SESSION['_SITE_']['profiledata']['message']['error']) : ?>
  <p class="error">
    <?= ($_SESSION['_SITE_']['profiledata']['message']['error']['pass_len'] ? 'Длина пароля должна быть не менее 3-х символов!<br>' : '') ?>
    <?= ($_SESSION['_SITE_']['profiledata']['message']['error']['pass_eq'] ? 'Пароли не совпадают!<br>' : '') ?>
    <?= ($_SESSION['_SITE_']['profiledata']['message']['error']['email_hash'] ? 'Неверный код подтверждения. Пройдите процедуру <a href="/profile/?cl=' . $this->getClassName() . '&tm=' . $this->tm_id . '&a=forgot_pwd">востановления пароля</a> еще раз!<br>' : '') ?>
  </p>
  <? endif ; ?>


  <? if ($code!='' && $email!='') : ?>

  <p>Введите новый пароль:</p>

  <form class="auth-form" action="/profile/" method="post">
    <input type="hidden" value="<?= $this->getClassName() ?>" name="cl">
    <input type="hidden" value="<?= $this->tm_id ?>" name="tm">
    <input type="hidden" value="forgot_pwd_change" name="a">
    <input type="hidden" value="<?= $code ?>" name="code">
    <input type="hidden" value="<?= $email ?>" name="e">
	<table style="width: 350px;">
		<tr>
			<td>Новый пароль:</td>
			<td><input type="password" value="" name="p1" style="width: 180px;"></td>
		</tr>
		<tr>
			<td>Подтверждение пароля :</td>
			<td><input type="password" value="" name="p2" style="width: 180px;"></td>
		</tr>
		<tr>
			<td></td>
			<td style="text-align: center;"><input type="submit" class="button" value="" /></td>
		</tr>
	</table>

    
  </form>

  <? else : ?>

  <p>Неверная ссылка! Пройдите процедуру <a href="/profile/?cl=<?= $this->getClassName() ?>&tm=<?= $this->tm_id ?>&a=forgot_pwd">восстановления пароля</a> еще раз!</p>

  <? endif ; ?>

<? endif ; ?>
</div>