<?#Шаблон формы поиска#?>
<form method="get" action="/search/">
  <input type="hidden" value="search_res" name="action">
  <input type="hidden" value="<?=$data['module_search_type']?>" name="tm">

  <input class="text poisk" type="text" name="q" value="<?=($_REQUEST['q'])?htmlspecialchars($_REQUEST['q']):"Поиск" ?>" tar="Поиск" autocomplete="off">
  <input class="butt" type="submit" value="">
  <a href="/advanced_search/" class="whide-search">расширенный поиск</a>
  <div class="clear"></div>
</form>
<script>
$(document).ready(function(){
        
        $('.poisk input[type=text]').focus(function(){
                if ($(this).attr('tar')==$(this).val())
                $(this).val("");
        });
        $('.poisk input[type=text]').blur(function(){
                if ($(this).val()=='')
                        $(this).val($(this).attr('tar'));
        });
});
		</script>
