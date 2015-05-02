<?#Страница формы авторизации пользователя сайта#?>

<div class="block-auth">

<? if ($_SESSION['_SITE_']['profiledata']['module_profile_item_id']>0) : ?>

  <div><a href="/profile/?cl=<?= $this->getClassName() ?>&tm=<?= $this->tm_id ?>&a=index">Здравствуйте<?= ($_SESSION['_SITE_']['profiledata']['p_fio']!='' ? ', '.$_SESSION['_SITE_']['profiledata']['p_fio'] : '') ?></a>!</div>
  <div><a href="/profile/?cl=<?= $this->getClassName() ?>&tm=<?= $this->tm_id ?>&a=logout">Выйти</a></div>

<? else : ?>

  <div><a href="javascript://" onclick="$('#block-auth').toggle();">Личный кабинет</a> <a href="/profile/?cl=<?= $this->getClassName() ?>&tm=<?= $this->tm_id ?>&a=reg_form">Регистрация</a></div>
  <div id="block-auth" style="display:none;">
    <b>Вход в личный кабинет</b><br>
    <form class="auth-form" action="/profile/" method="post">
      <input type="hidden" value="<?= $this->getClassName() ?>" name="cl">
      <input type="hidden" value="<?= $this->tm_id ?>" name="tm">
      <input type="hidden" value="login" name="a">
      <input type="hidden" value="" name="auth[g]" class="hidden" maxlength="50">

      <div>E-mail: <input type="text" value="" name="auth[e]"></div>
      <div>Пароль: <input type="password" value="" name="auth[p]"></div>
      <div><input type="checkbox" style="width:auto;" name="auth[r]" value="1"> запомнить авторизацию</div>
      <input type="submit" class="button" value="Войти" />
      <a href="/profile/?cl=<?= $this->getClassName() ?>&tm=<?= $this->tm_id ?>&a=forgot_pwd">Забыли пароль?</a>
    </form>
  </div>

<? endif ; ?>

</div>