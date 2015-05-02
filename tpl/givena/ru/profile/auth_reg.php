<?#Страница формы регистрации пользователя#?>

<h1>Регистрация</h1>

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

<script src="/tpl/givena/js/jquery.maskedinput-1.2.2.min.js"></script>
<style>
.profile-field-00{
  float:none !important;
}
p.error{color:red;}
</style>

<div class="form-contacts profile_edit reg_form">

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

  <table style="width:400px;">
    <tbody>
      <tr>
        <td class="label" style="width:110px;">Логин (e-mail)*:</td>
        <td><input type="text" value="<?= htmlspecialchars($_SESSION['_SITE_']['profiledata']['fields']['reg']['e']) ?>" name="reg[e]" maxlength="50" class="profile-field-00 profile-field-is-need-00"></td>
      </tr>
      <tr>
        <td class="label">Пароль*:</td>
        <td><input id="pwd-01" type="password" value="" name="reg[p]" maxlength="100" class="profile-field-00 profile-field-is-need-00"></td>
        <td><a href="javascript://" onclick="return setPwd();">Cгенерировать</a></td>
      </tr>
      <tr>
        <td class="label">Пароль еще раз*:</td>
        <td><input id="pwd-02" type="password" value="" name="reg[p2]" maxlength="100" class="profile-field-00 profile-field-is-need-00"></td>
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
        <td>&nbsp;</td>
        <td><input type="submit" class="button" value="" onclick="return test_user_reg();" /></td>
      </tr>
    </tbody>
  </table>
</form>

</div>

<script>
$(document).ready(function(){

    $('#p-p_phone').mask('+7(999)999-99-99');
    $('#p-p_date').mask('99.99.9999');

    var txt = 'индекс, адрес, дом, квартира';
    $('#p-p_adress').attr('tar', txt).val(txt);

    $('#p-p_adress').focus(function(){
      if ($(this).attr('tar')==$(this).val())
        $(this).val("");
    });
    $('#p-p_adress').blur(function(){
      if ($(this).val()=='')
        $(this).val($(this).attr('tar'));
    });

    setPwd = function()
    {
      var symRanges = ['A-Z', '0-9', 'a-z'],
      symbols = [],
      i, n,
      pass = '';
      length = 6;

      for( i=0, n=symRanges.length; i<n; ++i)
      {
        var range = symRanges[i].split('-');
        if(range.length == 2)
        {
          var stCode = range[0].charCodeAt(0),
          endCode = range[1].charCodeAt(0),
          tmp;
          if(stCode > endCode)
          {
            tmp = stCode;
            stCode = endCode;
            endCode = tmp;
          }
          for(var j=stCode, k=endCode; j<=k; ++j )
            symbols.push(String.fromCharCode(j));
        }
        else
          symbols.push(range[0]);
      }
      symbols = symbols.join('');
      for( i=0; i<length; ++i)
        pass += symbols.charAt(~~(Math.random() * symbols.length));

      $('#pwd-01, #pwd-02').val(pass);

      return false;
    }

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