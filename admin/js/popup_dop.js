
$(document).ready(function(){

	showBG = function(hide){
		//Ширина и высота всего документа
		var HeightDocument = $(document).height();
		var WidthDocument = $(document).width();

		//Плавное анимационное наложение на страницу серого фона
		$('.admin-popup-background').css({'width':WidthDocument,'height':HeightDocument});
		$('.admin-popup-background').fadeTo("fast",0.4).show();

		//Запрет на сколлинг страницы
		if (hide==1)
			$("body").css({"overflow":"hidden"});
	}

	closePopup = function(){
		$('.admin-popup-background, .admin-popup-window').hide().html("");
		$("body").css({"overflow":"auto"});
	}

});