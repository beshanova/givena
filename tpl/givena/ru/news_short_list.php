<div class="news-main">
<img src="/tpl/givena/images/twirl.gif" class="twirl" alt="" />
<h2>Новости</h2>
<a href="/<?= $data['module_menu_url'] ?>/" class="allnews">все новости</a>
<div>
   <? foreach ($catalog as $n) : ?>
	<div class="news-item">
		<p class="link"><a href="/<?= $data['module_menu_url'] ?>/?id=<?= $n['module_list_item_id'] ?>"><?= $n['f_title'] ?></a></p>
		<p class="anons"><?=$n['f_description'];?></p>
		<p class="date"><?php setlocale(LC_TIME , 'ru_RU.UTF-8'); print(strftime("%e %B %G", $n['f_date']));?></p>


	</div>
  <? endforeach ; ?>
  <p class="clear"></p>
</div>
</div>
