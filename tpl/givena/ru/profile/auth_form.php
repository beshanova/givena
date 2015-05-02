<?#Страница формы авторизации пользователя сайта#?>

<div class="block-auth" style="border-top:1px solid #80B61B;">

<? if ($_SESSION['_SITE_']['profiledata']['module_profile_item_id']>0) : ?>

  <div class="block-auth-mini">
    <p style="color: #0a5100;font-size: 14px;">Здравствуйте, <?= $_SESSION['_SITE_']['profiledata']['p_family'] ?>!</p>
	<a href="/profile/?cl=<?= $this->getClassName() ?>&tm=<?= $this->tm_id ?>&a=index"<?=($act=='index'?' class="act"':'')?> class="link">Список заказов</a>
	<a href="/profile/?cl=<?= $this->getClassName() ?>&tm=<?= $this->tm_id ?>&a=edit"<?=($act=='edit'?' class="act"':'')?> class="link2">Данные профиля</a>
    <input type="button" class="button exit" onclick="self.location='/profile/?cl=<?= $this->getClassName() ?>&tm=<?= $this->tm_id ?>&a=logout'" value="">
  </div>

<? else : ?>

  <h2 style="text-align:center; margin:10px 0 0 0;">Авторизация</h2>
  <form class="auth-form" action="/profile/" method="post">
    <input type="hidden" value="<?= $this->getClassName() ?>" name="cl">
    <input type="hidden" value="<?= $this->tm_id ?>" name="tm">
    <input type="hidden" value="login" name="a">
    <input type="hidden" value="1" name="auth[r]">
    <input type="hidden" value="" name="auth[g]" class="hidden" maxlength="50">

    <table>
      <tbody>
        <tr>
          <td class="label" style="width:75px;">E-mail:</td>
          <td><input type="text" class="text" value="<?= htmlspecialchars($_SESSION['_SITE_']['profiledata']['message']['auth_err_login']) ?>" name="auth[e]" id="auth-e-01"></td>
        </tr>
        <tr>
          <td class="label">Пароль:</td>
          <td><input type="password" class="text" value="" name="auth[p]" id="auth-p-01"></td>
        </tr>
        <tr>
          <td colspan="2">
			<a href="/profile/?cl=<?= $this->getClassName() ?>&tm=<?= $this->tm_id ?>&a=forgot_pwd">Забыли пароль?</a>
			<a href="/profile/?cl=<?= $this->getClassName() ?>&tm=<?= $this->tm_id ?>&a=reg_form" style="float: right;">Регистрация</a></td>
        </tr>
        <tr>
          <td colspan="2" style="text-align: center;"><input type="submit" class="button enter" value="" /></td>
        </tr>
      </tbody>
    </table>
  </form>

<? endif ; ?>

</div>