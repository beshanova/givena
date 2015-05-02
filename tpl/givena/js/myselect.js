$(document).ready(function(){

$('body').click(function() {
    $("ul.ulselect").hide();
});
$('.myselect').click(function(event) {
    var i = 0;
    if ($(this).next("ul.ulselect").is(':visible') || $(this).next().next("ul.ulselect").is(':visible')) i = 1;
    $("ul.ulselect").hide();
    event.stopPropagation();
    if (i)
    {
     $(this).next("ul.ulselect").hide();
     $(this).next().next("ul.ulselect").hide();
    }
    else {
     $(this).next("ul.ulselect").show();
     $(this).next().next("ul.ulselect").show();
    }
});

$('ul.ulselect li').click(function() {
     var t = $(this).html();
     if (t!='')
     {
      $(this).parent().prev("img").prev("input").prev("input").val($(this).attr("name"));
      $(this).parent().prev("img").prev("input").val(t);
      $('ul.ulselect').hide();

	  $('#sort-form01').submit();
/*
	  if (cost_ur)
	  {
		  var mm = $(this).attr('name');
		  mm = (mm!=1)?mm:'bud';
		  $('#cost-tv01').text(cost_ur.tv[mm]);
		  $('#cost-site-tv01').text(cost_ur.site_tv[mm]);
	  }*/
     }
});

});