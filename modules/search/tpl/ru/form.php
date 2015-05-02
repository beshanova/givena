<?#Шаблон формы поиска (default)#?>

<div style="margin-top:-30px;">
Поиск по сайту:
<form method="post" action="/search/">
  <input type="hidden" name="cl" value="Search">
  <input type="hidden" value="search_res" name="action">
  <input type="hidden" value="<?=$data['module_search_type']?>" name="tm">

  <input type="text" name="q" value="<?= htmlspecialchars($_REQUEST['q']) ?>">
  <input type="submit" value="Найти">
</form>
</div>