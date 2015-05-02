<?#Страница восстановления пароля#?>

<link type="text/css" rel="stylesheet" href="/tpl/<?= $_SESSION['_SITE_']['theme'] ?>/css/profile.css">

<h1>Восстановление пароля</h1>

<div class="block-auth forgot_pwd">

<? if ($_SESSION['_SITE_']['profiledata']['message']['success_send']) : ?>

  <p class="success">На указанный E-mail выслано письмо со ссылкой на страницу изменения пароля.</p>

<? else : ?>

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

    <table>
      <tbody>
        <tr>
          <td style="border:0;"><input type="text" value="<?= htmlspecialchars($_SESSION['_SITE_']['profiledata']['message']['email']) ?>" name="email" class="text" style="width:150px;"></td>
        </tr>
      </tbody>
    </table>

    <input type="submit" class="button" value="" />
  </form>


<? endif ; ?>

</div>