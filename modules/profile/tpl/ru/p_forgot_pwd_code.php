<?#Страница изменения пароля после перехода по ссылке из письма#?>

<link type="text/css" rel="stylesheet" href="/tpl/<?= $_SESSION['_SITE_']['theme'] ?>/css/profile.css">

<h2>Изменение пароля</h2>

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

    <div>Новый пароль: <input type="password" value="" name="p1"></div>
    <div>Подтверждение пароля : <input type="password" value="" name="p2"></div>

    <input type="submit" class="button" value="Изменить" />
  </form>

  <? else : ?>

  <p>Неверная ссылка! Пройдите процедуру <a href="/profile/?cl=<?= $this->getClassName() ?>&tm=<?= $this->tm_id ?>&a=forgot_pwd">восстановления пароля</a> еще раз!</p>

  <? endif ; ?>

<? endif ; ?>