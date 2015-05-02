$(document).ready(function(){
	counterup = function(id, tar)
	{
		$('#'+tar+''+id).val( parseInt($('#'+tar+''+id).val(), 10)+1 );
		if (tar=='counter-b')
		{
			UpdateIn2Basket(id);
		}
	}

	counterdown = function(id, tar)
	{
	  if ( parseInt($('#'+tar+''+id).val(), 10)>1 )
	  {
		$('#'+tar+''+id).val( parseInt($('#'+tar+''+id).val(), 10)-1 );
		if (tar=='counter-b')
		{
			UpdateIn2Basket(id);
		}
	  }
	}

	showProcessing = function(cl, pcl)
	{
		$(cl).after('<div class="root-choice"><img alt="" src="/tpl/givena/images/root.png" class="root-2"><h3>Товар добавлен <a href="javascript://" onclick="return ShowBasketOrder(\''+pcl+'\');">в корзину</a></h3></div>');
		setTimeout("$('div.root-choice').fadeOut(500);", 1000);
	}

	UpdateIn2Basket = function(id)
	{
		var sm_all = sm_cnt = 0;
		$('.counts-tr-00').each(function(){
			var ido = $(this).attr('cpid');
			var cnt = parseInt($(this).val(), 10);
			var price = $(this).attr('price');

			sm_cnt += cnt;
			sm_all += price*cnt;
			
			$('#summ-tr-'+ido).text( Math.round(price*cnt*100)/100 );
		});
		$('#cnt-all-00').text(sm_cnt);
		$('#summ-all-00').text( Math.round(sm_all*100)/100 );
	}

	Add2Basket = function (id, tm, t, pr)
	{
		$(document).unbind('click');
		if (pr>0)
		{
			$('#root-choice-'+id).hide();
			$.ajax({
				url: "/",
				type: "POST",
				data: { action:'add2basket', tm:tm, id_item:id, ajax:1, cnt:1, pr:pr, bu:$('#bu-'+id).val() },
				dataType : 'html',
				cache : false,
				success: function(html){
					$('div.root-choice').hide();
					if (html!='err')
						$('#basket-block01').html(html);
					else
						alert('Ошибка: код '+html);
					showProcessing(t, tm);
				},
				beforeSend: function(){},
				error: function(errorData){ alert('Ошибка! Перезагрузите страницу!'); }
			});
		}
		else
		{
			$('div.root-choice').hide();
			$(t).parent().parent().find('#root-choice-'+id).fadeIn(500);
			setTimeout(hideRootChoice, 100);
		}
		return false;
	}

	hideRootChoice = function()
	{
		$(document).bind('click', function(){
			if ($('div.root-choice').is(':visible'))
			{
				$('div.root-choice').hide();
				$(document).unbind('click');
			}
		});
	}

	changeSelectBasket = function()
	{
	  $('#b-b_deliv').change(function(){
		  var deliv = $(this).val();

		  $('#b-b_index').removeClass('basket-field-is-need-00');
		  $('#tr-index-01 span').hide();

		  if (deliv!='Самовывоз - 0 руб.')
		  {
			if (deliv=='Доставка почтой - рассчитывается индивидуально')
			{
				$('#b-b_index').addClass('basket-field-is-need-00');
				$('#tr-index-01 span').show();
			}
			$('#b-b_adress').addClass('basket-field-is-need-00');
			$('#tr-adress-01 span').show();
		  }
		  else
		  {
			$('#b-b_adress').removeClass('basket-field-is-need-00');
			$('#tr-adress-01 span').hide();
		  }
	  });
	}

	ShowBasketOrder = function (tm)
	{
		$.ajax({
			url: "/basket/",
			type: "POST",
			data: { action:'basket_show', ajax:1, tm:tm },
			dataType : 'html',
			cache : false,
			success: function(html){
				//$('#basket-order-list01').html(html).fadeIn(500);\
				$('#popup_reference1 .inner_reference').html(html).fadeIn(500);
				showPopupFrontEnd('reference1');
				changeSelectBasket();
                updateLiveInternetCounter();
                yaCounter20442349.hit('http://givena.ru/basket/');
			},
			beforeSend: function() {},
			error: function(errorData){ alert('Ошибка! Перезагрузите страницу!'); }
		});
		return false;
	}
    
    ShowBasketConfirm = function (tm, rose)
	{
        rose = rose || 0;
		$.ajax({
			url: "/confirm/",
			type: "POST",
			data: { action:'basket_show', ajax:1, tm:tm },
			dataType : 'html',
			cache : false,
			success: function(html){
			     if (rose) $('select#b-b_deliv option[value="Доставка почтой - рассчитывается индивидуально"]').attr('disabled', 'disabled');
				$('#basket-order-form01').fadeIn(500);
				
                updateLiveInternetCounter();
                yaCounter20442349.hit('http://givena.ru/confirm/');
			},
			beforeSend: function() {},
			error: function(errorData){ alert('Ошибка! Перезагрузите страницу!'); }
		});
		return false;
	}

	//-- удаление товара из корзины
	deleteItem = function(id, tm)
	{
		$.ajax({
			url: "/",
			type: "POST",
			data: { action:'del2basket', tm:tm, id_item:id, ajax:1 },
			dataType : 'html',
			cache : false,
			success: function(html){
				//$('#order-string-'+id).remove();
				//$('#basket-order-list01').html(html);
				$('#popup .inner_reference').html(html);
			},
			beforeSend: function(){},
			error: function(errorData){
				alert('Ошибка! Перезагрузите страницу!');
			}
		});
		return false;
	}

	testDataBasket = function()
	{
		var met = true;
		$('.basket-field-is-need-00').each(function(){
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
			var t_fio = $('#b-b_fio').val();
			var t_email = $('#b-b_email').val();
			var t_phone = $('#b-b_phone').val();
			var t_ind = $('#b-b_index').val();
			if (t_fio!="" && ! /^[a-zйцукенгшщзхъэждлорпавыфячсмитьбюёЙЦУКЕНГШЩЗХЪЭЖДЛОРПАВЫФЯЧСМИТЬБЮЁ\s\-\.]+$/i.test(t_fio) )
			{
				alert('Неверный формат поля "Имя"!');
				met = false;
				$('#b-b_fio').focus();
			}
			//-- проверка поля "Email"
			else if (t_email=="" || ! /^.+\@.+\..+$/.test(t_email) )
			{
				alert('Неверный формат поля "E-mail"!');
				met = false;
				$('#b-b_email').focus();
			}
			//-- проверка поля "Телефон"
			else if (t_phone!="" && ! /^[0-9\(\s\)\-]+$/.test(t_phone) )
			{
				alert('Неверный формат поля "Телефон"!');
				met = false;
				$('#b-b_phone').focus();
			}
			//-- проверка поля "Индекс"
			else if (t_ind!="" && ! /^[0-9]+$/.test(t_ind) )
			{
				alert('Неверный формат поля "Индекс"!');
				met = false;
				$('#b-b_index').focus();
			}
		}
		return met;
	}

});

function updateLiveInternetCounter()
{
 var liCounter = new Image(1,1);
 liCounter.src = '//counter.yadro.ru/hit?r='+
 ((typeof(screen)=='undefined')?'':';s'+screen.width+
 '*'+screen.height+'*'+(screen.colorDepth?screen.colorDepth:
 screen.pixelDepth))+';u'+escape(document.URL)+
 ';'+Math.random();
}
