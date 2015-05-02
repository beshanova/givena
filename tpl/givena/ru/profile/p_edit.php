<?#Страница редактирования профиля#?>

<? $act='edit'; include('./tpl/'.$_SESSION['_SITE_']['theme'].'/ru/profile/inc_menu.php'); ?>

<h2>Редактирование профиля</h2>

<script src="/tpl/givena/js/jquery.maskedinput-1.2.2.min.js"></script>

<div class="form-contacts profile_edit" style="padding-top:0; border:0;">

<? if ($_SESSION['_SITE_']['profiledata']['message_p']['save'] && !sizeof($_SESSION['_SITE_']['profiledata']['message_p']['error'])) : ?>
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
  <?= ($_SESSION['_SITE_']['profiledata']['message_p']['error']['pass_new1'] ? 'Длина нового пароля должна быть не менее 6 символов!<br>' : '') ?>
  <?= ($_SESSION['_SITE_']['profiledata']['message_p']['error']['pass_new2'] ? 'Новые пароли не совпадают!<br>' : '') ?>
</p>
<? endif ; ?>


<form class="auth-form" action="/profile/" method="post" id="reg-form-00">
  <input type="hidden" value="<?= $this->getClassName() ?>" name="cl">
  <input type="hidden" value="<?= $this->tm_id ?>" name="tm">
  <input type="hidden" value="edit_profile_save" name="a">

  <table style="width:290px;">
    <tbody>
      <tr>
        <td class="label">Логин (e-mail)*:</td>
        <td><input type="text" value="<?= htmlspecialchars($profile['module_profile_item_email']) ?>" class="profile-field-00 profile-field-is-need-00" disabled></td>
      </tr>

    <? foreach ($fields as $f) : ?>
      <tr>
        <td class="label"><?= $f['title'] ?>:</td>
        <td>
        <? if (is_array($f['field'])) : ?>
          <?= implode('<br />', $f['field']) ?>
        <? else : ?>
          <?= $f['field'] ?>
        <? endif ; ?>
        </td>
      </tr>
    <? endforeach ; ?>

      <tr>
        <td class="label">&nbsp;</td>
        <td><a href="javascript://" onclick="$('tr.pwd-00').toggle();">изменить пароль</a></td>
      </tr>
      <tr class="pwd-00">
        <td class="label">Старый пароль*:</td>
        <td><input type="password" value="" name="pass[p0]" maxlength="100" class="profile-field-00 profile-field-is-need-00"></td>
      </tr>
      <tr class="pwd-00">
        <td class="label">Новый пароль*:</td>
        <td><input type="password" value="" name="pass[p1]" maxlength="100" class="profile-field-00 profile-field-is-need-00"></td>
      </tr>
      <tr class="pwd-00">
        <td class="label">Подтверждение пароля*:</td>
        <td><input type="password" value="" name="pass[p2]" maxlength="100" class="profile-field-00 profile-field-is-need-00"></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td style="text-align: center;"><input type="submit" class="button" value="" /></td>
      </tr>

    </tbody>
  </table>
</form>

</div>
<div class="clear"></div>

<script>
$(document).ready(function(){
  $('#p-p_phone').mask('+7(999)999-99-99');
  $('#p-p_date').mask('99.99.9999');
});
</script>