  jQuery.fn.maska = function(classa) {

  $(this).hover(function(){
      $(this).find(classa).stop().animate({height:'25px',opacity: '0.8'}, 400);
      },function () {
        $(this).find(classa).stop().animate({height:'0',opacity: '0'}, 400);
        });
  }

	$(document).ready(function(){
		var is_main = $("#url").html();	
		if (is_main=='/')
		{
			$('.box').maska('.maska'); 
			var t = document.getElementById('features'); 
			var theight = t.offsetHeight; 
		//	alert(theight);
			$('#features .lb').css("height", (theight?theight:277));
			$('#properties .lb').css("height", theight);
			$('#invitation .lb').css("height", theight);
		}
		$('#opaco').click(function() {
			$('#popup').addClass('hidden');
			$('#opaco').addClass('hidden').removeAttr('style');
		});
	}); 
	
	 $(".lastblock").hover(
      function () {
        $(this).css({ backgroundColor:"#e9ffdb" });
      }, 
      function () {
        $(this).css({ backgroundColor:"#faf9f2"});
      }
    );


 //additional properties for jQuery object
$(document).ready(function(){
 //align element in the middle of the screen
 $.fn.alignCenter = function() {
 //get margin left
 var left = Math.max(40, parseInt($(window).width()/2 - $(this).width()/2)) + 'px';
 //get margin top
 var top = Math.max(40, parseInt($(window).height()/2 - $(this).height()/2));
 //return updated element
 var winH = document.documentElement.clientHeight;
 var winW = document.documentElement.clientWidth;
 var h = $(this).height();
 var w = $(this).width();
 
 var diff = winH - top - h;
 if (diff<0) top +=diff;

 if (top<0) top = 0;
 top += 'px';
 if (winH<h)
	$(this).css({'max-height':winH, 'overflow-y': 'scroll'});
	
 if (winW<w)
	$(this).css({'width':winW-20, 'overflow-x': 'scroll'});
	//$('.reference').css({'width':winW-20});
 
 return $(this).css({'left':left, 'top':top});
 };

});
//close pop-up box
function closePopupFrontEnd()
{
	$('#opaco').toggleClass('hidden').removeAttr('style');
	$('#popup').toggleClass('hidden');
	$(".inner_form").show();
    $(".send_mess_1").empty().hide();
	$(".send_mess_2").empty().hide();
	return false;
}

//open pop-up
function showPopupFrontEnd(popup_type)
{
  //when IE - fade immediately
	if($.browser.msie)
	{
	$('#opaco').height($(document).height()).toggleClass('hidden');
	}
	else
	//in all the rest browsers - fade slowly
	{
	$('#opaco').height($(document).height()).toggleClass('hidden');//fadeTo('slow', 0.7);
	}
   
	$('#popup').html($('#popup_' + popup_type).html());//.alignCenter().toggleClass('hidden');
	//$('#popup_' + popup_type).find('form').attr('id', "");
	$('#popup_' + popup_type + ' .inner_reference').html('');
	$('#popup').alignCenter();
	$('#popup').toggleClass('hidden');
	return false;
}

String.prototype.PA = function (_hamper,_prefix,_postfix,_face)
{
	_hamper=_prefix+"@"+this+(_postfix || '');
	document.write(_face+(_hamper).link("mailto:"+_hamper));
}