
$(document).ready(function(){

	var loading = '<img src="/admin/img/load.gif" border="0" id="preload-img" />';

	$('.admin-div-icon-action').click(function() {
		return loadPopup('edit_block', $(this));
	});

	$('.admin-div-icon-action-item-edit').click(function() {
		return loadPopup('item', $(this));
	});

	$('.admin-div-icon-action-add-block').click(function() {
		return loadPage('add_block', $(this));
	});
	
	// оптимизировать 
	
	
	//всплывашка
	jQuery(".admin-div-block-border, .admin-div-icon-action-block").hover(
    function () 
    {
      jQuery(this).find('.admin-div-icon-action-block').fadeIn(1);
    },
    function () 
    {
      jQuery(this).find('.admin-div-icon-action-block').fadeOut(1);
    }
	);
	
	jQuery(".admin-div-block-border .admin-div-block-border").hover(
    function () 
    {
      jQuery(this).find('.admin-div-icon-action-block-item').fadeIn(1);
    },
    function () 
    {
      jQuery(this).find('.admin-div-icon-action-block-item').fadeOut(1);
    }
	);
	
	//всплывашка
	jQuery(".admin-div-block-border-no-active, .admin-div-icon-action-block").hover(
    function () 
    {
      jQuery(this).find('.admin-div-icon-action-block').fadeIn(1);
    },
    function () 
    {
      jQuery(this).find('.admin-div-icon-action-block').fadeOut(1);
    }
	);
	
	jQuery(".admin-div-block-border .admin-div-block-border-no-active").hover(
    function () 
    {
      jQuery(this).find('.admin-div-icon-action-block-item').fadeIn(1);
    },
    function () 
    {
      jQuery(this).find('.admin-div-icon-action-block-item').fadeOut(1);
    }
	);
	
	jQuery("#wrapper-top .admin-panel-adm").hover(
    function () 
    {
      jQuery(this).find('.admin-panel-adm-hide').fadeIn(1);
    },
    function () 
    {
      jQuery(this).find('.admin-panel-adm-hide').fadeOut(1);
    }
	);

	
	// выпадашка 
	
	$(document).ready(function()
	{
	$(".account").hover(function()
	{
	var X=$(this).attr('id');

	if(X==1)
	{
	$(".submenu").hide(1);
	$(this).attr('id', '0');	
	}
	else
	{

	$(".submenu").show();
	$(this).attr('id', '1');
	}
	});

	$(document).mouseup(function()
	{
	$(".submenu").hide(1);
	$(".account").attr('id', '');
	});
	});
	
	
	$(document).ready(function()
	{
	$(".account-item").hover(function()
	{
	var X=$(this).attr('id');
	if(X==1)
	{
	$(".submenu-item").hide(1);
	$(this).attr('id', '0');	
	}
	else
	{
	$(".submenu-item").show();
	$(this).attr('id', '1');
	}
	});

	$(document).mouseup(function()
	{
	$(".submenu-item").hide(1);
	$(".account-item").attr('id', '');
	});
	});
	
	
	
	// - оптимизировать - 
	
	
	

	
	loadPopup = function(p, t)
	{
		$.ajax({
		  url: '/_ajax/',
		  data: { d:p, cl:t.attr('cl'), tm:t.attr('tm'), 'item_id':t.attr('item') },
		  type: "POST",
		  cache: false,
		  success: function(data)
		  {
			$('.admin-popup-window').html(data);
			showPopup();
		  },
		  beforeSend: function()
		  {
			$('.admin-popup-window').html(loading);
		  }
		});			
		return false;
	}

	loadPage = function(p, t)
	{
		if ($('#admin-div-block-add-type').css('display')=='block')
		{
			$('#admin-div-block-add-type').hide();
		}
		else
			$.ajax({
			  url: '/_ajax/',
			  data: { d:p, cl:t.attr('cl') },
			  type: "POST",
			  cache: false,
			  success: function(data)
			  {
				$('#admin-div-block-add-type').html(data).show();
			  },
			  beforeSend: function() {}
			});
		return false;
	}
	
	// Перетаскивание окна всплывашки
	$(function() {
        $( "#draggable" ).draggable({ cancel: ".admin-popup-window-form" });
    });
	

	// прячем всплывашку добавления блока
	//$(document).mouseup(function()
	//{
	//$("#admin-div-block-add-type").hide();
	//});
	
	// редактирование блока по двойному клику
	$(".admin-div-block-border").dblclick( function () 
		{
			return loadPopup('edit_block', $(this).children('.admin-div-icon-action-block').children('.admin-div-icon-action'));
		}
	);
	
	// редактирование блока по двойному клику в новостях
	$(".admin-div-block-border .admin-div-block-border").dblclick( function () 
		{
			return loadPopup('edit_block', $(this).children('.admin-div-icon-action-block-item').children('.admin-div-icon-action'));
		}
	);
	
	// редактирование блока по двойному клику
	$(".admin-div-block-border-no-active").dblclick( function () 
		{
			return loadPopup('edit_block', $(this).children('.admin-div-icon-action-block').children('.admin-div-icon-action'));
		}
	);
	
	// редактирование блока по двойному клику в новостях
	$(".admin-div-block-border .admin-div-block-border").dblclick( function () 
		{
			return loadPopup('edit_block', $(this).children('.admin-div-icon-action-block-item').children('.admin-div-icon-action'));
		}
	);
	
	function findDimensions(){
		var height = 0; // переменные с шириной и высотой окна
		if(window.innerWidth)
		{ // если браузер поддерживает метод window.innerWidth
			height = window.innerHeight; // присвоить высоту методом window.innerWidth
		} // иначе если браузер не поддерживает метод window.innerWidth,
		else if(document.body && document.body.clientWidth)
		{ // то если браузер поддерживает объект document.body и метод .clientWidth
			height = document.body.clientHeight; // присвоить высоту методом document.body.clientWidth
		}
		if(document.documentElement && document.documentElement.clientWidth)
		{ // если поддерживает метод document.documentElement.clientWidth
			height = document.documentElement.clientHeight; // присвоить высоту методом document.documentElement.clientWidth
		}
		return height;
	}

	showPopup = function(){
		//Ширина и высота окна браузера
		var HeightScreen = window.innerHeight;
				
		//Расположение модального окна с содержимым по высоте учитывая скроллинг документа
		var Top_popup_window = $(window).scrollTop() + HeightScreen/2 - $('.admin-popup-window').height()/2;
		var Left_popup_window = -1*$('.admin-popup-window').width()/2;

		showBG( (HeightScreen-50>$('.admin-popup-window').height() ? 1 : 0) );

		$('.admin-popup-window').css({'top':Top_popup_window+'px', 'display':'block', 'margin-left':Left_popup_window+'px'});

		$('body').keyup(function(e) {
			if(e.which===27){ $('.admin-popup-background, .admin-popup-window').hide();
			$("body").css({"overflow":"auto"});} 
		});

		//При клике на кнопке "Close Window", модальное окно и фон скрываются
		$(' .admin-popup-window-button-close').click(function () {
			closePopup();
		});
	}

});