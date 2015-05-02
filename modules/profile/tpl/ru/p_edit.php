<?#Страница редактирования профиля#?>

<? include('./tpl/'.$_SESSION['_SITE_']['theme'].'/ru/profile/inc_menu.php'); ?>

<p><b>Редактирование профиля</b></p>

<? if ($_SESSION['_SITE_']['profiledata']['message_p']['save']) : ?>
<p class="success">
  <?= ($_SESSION['_SITE_']['profiledata']['message_p']['save'] ? 'Данные профиля обновлены!<br>' : '') ?>
  <?= ($_SESSION['_SITE_']['profiledata']['message_p']['save_pass'] ? 'Пароль изменен!<br>' : '') ?>
</p>
<? endif ; ?>

<? if (sizeof($_SESSION['_SITE_']['profiledata']['message_p']['error'])>0) : ?>
<p class="error">
  <?= ($_SESSION['_SITE_']['profiledata']['message_p']['error']['all'] ? 'Не все обязательные поля заполнены!<br>' : '') ?>
  <?= ($_SESSION['_SITE_']['profiledata']['message_p']['error']['pass_old1'] ? 'Новый пароль должен отличаться от старого!<br>' : '') ?>
  <?= ($_SESSION['_SITE_']['profiledata']['message_p']['error']['pass_old2'] ? 'Старый пароль введен неверно!<br>' : '') ?>
  <?= ($_SESSION['_SITE_']['profiledata']['message_p']['error']['pass_new1'] ? 'Длина нового пароля должна быть 3 или более символов!<br>' : '') ?>
  <?= ($_SESSION['_SITE_']['profiledata']['message_p']['error']['pass_new2'] ? 'Новые пароли не совпадают!<br>' : '') ?>
</p>
<? endif ; ?>


<form class="auth-form" action="/profile/" method="post" id="reg-form-00">
  <input type="hidden" value="<?= $this->getClassName() ?>" name="cl">
  <input type="hidden" value="<?= $this->tm_id ?>" name="tm">
  <input type="hidden" value="edit_profile_save" name="a">

  <div>E-mail (Логин): <input type="text" value="<?= htmlspecialchars($profile['module_profile_item_email']) ?>" class="profile-field-00 profile-field-is-need-00" disabled></div>

  <? foreach ($fields as $f) : ?>

  <div>
  <?= $f['title'] ?>:
  <? if (is_array($f['field'])) : ?>
      <?= implode('<br />', $f['field']) ?>
  <? else : ?>
      <?= $f['field'] ?>
  <? endif ; ?>
  </div>

  <? endforeach ; ?>

  <hr>
  <b>Сменить пароль:</b>
  <div>Старый пароль*: <input type="password" value="" name="pass[p0]" maxlength="100" class="profile-field-00 profile-field-is-need-00"></div>
  <div>Новый пароль*: <input type="password" value="" name="pass[p1]" maxlength="100" class="profile-field-00 profile-field-is-need-00"></div>
  <div>Подтверждение пароля*: <input type="password" value="" name="pass[p2]" maxlength="100" class="profile-field-00 profile-field-is-need-00"></div>

  <br>
  <input type="submit" class="button" value="Сохранить" />
</form>