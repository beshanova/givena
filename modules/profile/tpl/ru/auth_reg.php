<?#Страница формы регистрации пользователя#?>

<? if ($_SESSION['_SITE_']['profiledata']['module_profile_id']>0) : ?>

<p>Вы уже зарегистрированы... Перейдите в <a href="/profile/">профиль</a></p>

<? elseif ($_SESSION['_SITE_']['profiledata']['message']['success']>0) : ?>

  <? if ($_SESSION['_SITE_']['profiledata']['message']['success']==1) : ?>
    <p>Вы успешно зарегистрированы на сайте! На указанный E-mail выслано письмо со ссылкой для подтверждения регистрации!</p>
  <? elseif ($_SESSION['_SITE_']['profiledata']['message']['success']==2) : ?>
    <p>Вы успешно зарегистрированы на сайте! Как только Ваша регистрация будет подтверждена администратором, на указанный E-mail будет выслано письмо.</p>
  <? elseif ($_SESSION['_SITE_']['profiledata']['message']['success']==3) : ?>
    <p>Вы успешно зарегистрированы на сайте! Авторизуйтесь, чтобы зайти в личный кабинет.</p>
  <? elseif ($_SESSION['_SITE_']['profiledata']['message']['success']==13) : ?>
    <p>Ваш профиль активирован! Авторизуйтесь, чтобы зайти в личный кабинет.</p>
  <? endif ; ?>

<? else : ?>

<style>
.profile-field-00{
  float:none !important;
}
p.error{color:red;}
</style>

  <h2>Регистрация</h2>

  <? if (sizeof($_SESSION['_SITE_']['profiledata']['message']['error'])>0) : ?>
  <p class="error">
    <?= ($_SESSION['_SITE_']['profiledata']['message']['error']['all'] ? 'Не все обязательные поля заполнены!<br>' : '') ?>
    <?= ($_SESSION['_SITE_']['profiledata']['message']['error']['email'] ? 'E-mail введен неверно или уже занят!<br>' : '') ?>
    <?= ($_SESSION['_SITE_']['profiledata']['message']['error']['pass'] ? 'Введенные пароли не совпадают!<br>' : '') ?>
    <?= ($_SESSION['_SITE_']['profiledata']['message']['error']['pass2'] ? 'Длина пароля должна быть не меньше 3-х символов!<br>' : '') ?>
    <?= ($_SESSION['_SITE_']['profiledata']['message']['error']['unknow'] ? 'Неизвестная ошибка!<br>' : '') ?>
    <?= ($_SESSION['_SITE_']['profiledata']['message']['error']['confirm'] ? 'Ошибка подтверждения E-mail (неверный код)!<br>' : '') ?>
  </p>
  <? endif ; ?>

  <form class="auth-form" action="/profile/" method="post" id="reg-form-00">
    <input type="hidden" value="<?= $this->getClassName() ?>" name="cl">
    <input type="hidden" value="<?= $this->tm_id ?>" name="tm">
    <input type="hidden" value="reg" name="a">

    <div><input type="hidden" value="" name="reg[g]" maxlength="50" class="profile-field-00 profile-field-is-need-00"></div>
    <div>E-mail (Логин)*: <input type="text" value="<?= htmlspecialchars($_SESSION['_SITE_']['profiledata']['fields']['reg']['e']) ?>" name="reg[e]" maxlength="50" class="profile-field-00 profile-field-is-need-00"></div>
    <div>Пароль*: <input type="password" value="" name="reg[p]" maxlength="100" class="profile-field-00 profile-field-is-need-00"></div>
    <div>Подтверждение пароля*: <input type="password" value="" name="reg[p2]" maxlength="100" class="profile-field-00 profile-field-is-need-00"></div>

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

    <br>
    <input type="submit" class="button" value="Зарегистрироваться" onclick="return test_user_reg();" />
  </form>

  <script>
  $(document).ready(function(){

    test_user_reg = function()
    {
      var data = {};
      $('input.profile-field-is-need-00').each(function(){
        var n = $(this).attr('name');
        n = n.replace(/^reg\[([a-z0-9]+)\]$/i, '$1');
        data[n] = $(this).val();
      });

      $.ajax({
        url: '/',
        data: { cl:'<?=$this->getClassName()?>', tm:'<?= $this->tm_id ?>', a:'reg_test', reg:data },
        type: "POST",
        dataType:"json",
        cache: false,
        success: function(data)
        {
          console.log(data);
          if (data[0]=='ok')
          {
            $('#reg-form-00').submit();
          }
          else
          {
            var err_mes = '';
            if (data.all)
              err_mes += "Не все обязательные поля заполнены!\n";
            if (data.email)
              err_mes += "E-mail введен неверно или уже занят!\n";
            if (data.pass)
              err_mes += "Введенные пароли не совпадают!\n";
            if (data.pass2)
              err_mes += "Длина пароля должна быть не меньше 3-х символов!\n";
            if (data.unknow)
              err_mes += "Неизвестная ошибка!";
            if (err_mes != '')
              alert(err_mes);
          }
        },
        beforeSend: function(){}
      });
      return false;
    }

  });
  </script>

<?endif;?>