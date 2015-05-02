
$(document).ready(function(){

	testDataCatalog = function(tp){
		var met_is_add1 = true;
		var met_is_add2 = false;
		//-- cмысл: если хоть 1 поле заполнено, то значит происходит добавление/редактирование записи и нужно проверять также и обязательные поля
		$('.catalog-field-00').each(function(){
			if ($(this).val()!="")
				met_is_add2 = true;
		});
		if (met_is_add2 || tp=='edit')
		{
			$('.catalog-field-is-need-00').each(function(){
				if ($(this).val()=="" && met_is_add1)
				{
					alert('Не все обязательные поля заполнены!');
					$(this).focus();
					met_is_add1 = false;
				}
			});			
		}
		return met_is_add1;
	}

	$(function() {
        $( "#tabs" ).tabs();
    });

    $(function() {
        $( ".admin-field-style-dtext" ).datepicker({
            showOn: "button",
            buttonImage: "/admin/img/calendar.gif",
            buttonImageOnly: true
        });
    });

    $(function() {
        $(".admin-field-style-dttext").datetimepicker();
    });

});