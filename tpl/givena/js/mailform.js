testDataMailform = function()
{
	var met = true;
	$('.mailform-field-is-need-00').each(function(){
		if ($(this).val()=="" && met)
		{
			alert('Не все обязательные поля заполнены!');
			$(this).focus();
			met = false;
		}
	});

	if (met)
	{
		//-- проверка поля "Имя"
		var t_fio = $('#f-f_fio').val();
		var t_email = $('#f-f_email').val();
		var t_phone = $('#f-f_phone').val();
		if (t_fio!="" && ! /^[a-zйцукенгшщзхъэждлорпавыфячсмитьбюёЙЦУКЕНГШЩЗХЪЭЖДЛОРПАВЫФЯЧСМИТЬБЮЁ\s\-\.]+$/i.test(t_fio) )
		{
			alert('Неверный формат поля "Имя"!');
			met = false;
			$('#f-f_fio').focus();
		}
		//-- проверка поля "Email"
		else if (t_email!="" && ! /^.+\@.+\..+$/.test(t_email) )
		{
			alert('Неверный формат поля "E-mail"!');
			met = false;
			$('#f-f_email').focus();
		}
		//-- проверка поля "Телефон"
		else if (t_phone!="" && ! /^[0-9\(\s\)\-]+$/.test(t_phone) )
		{
			alert('Неверный формат поля "Телефон"!');
			met = false;
			$('#f-f_phone').focus();
		}
	}

	return met;
}