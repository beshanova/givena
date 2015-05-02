<?#Страница восстановления пароля#?>

<link type="text/css" rel="stylesheet" href="/tpl/<?= $_SESSION['_SITE_']['theme'] ?>/css/profile.css">

<h2>Восстановление пароля</h2>

<? if ($_SESSION['_SITE_']['profiledata']['message']['error']) : ?>
<p class="error">
  <?= ($_SESSION['_SITE_']['profiledata']['message']['error']['email_er'] ? 'E-mail введен неверно!<br>' : '') ?>
  <?= ($_SESSION['_SITE_']['profiledata']['message']['error']['email_no'] ? 'Данный E-mail не зарегистрирован в системе!<br>' : '') ?>
</p>
<? endif ; ?>

<p>Укажите Ваш E-mail при регистрации:</p>

<form class="auth-form" action="/profile/" method="post">
  <input type="hidden" value="<?= $this->getClassName() ?>" name="cl">
  <input type="hidden" value="<?= $this->tm_id ?>" name="tm">
  <input type="hidden" value="forgot_pwd_send" name="a">

  <div>E-mail: <input type="text" value="<?= htmlspecialchars($_SESSION['_SITE_']['profiledata']['message']['email']) ?>" name="email"></div>

  <input type="submit" class="button" value="Выслать" />
</form>

<p>На указанный E-mail будет выслана ссылка на страницу изменения пароля.</p>