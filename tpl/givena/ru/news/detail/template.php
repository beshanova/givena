
<h1><?= $catalog['f_title'] ?></h1>
	<div class="news-list-item inn-news">
	<?if ($catalog['f_file_data']):?>
		<img src="/files/resize_cache<?= $catalog['f_file_data']['dirname'] . '318x227-crop_'.$catalog['f_file_data']['name'] ?>" alt="" />
	<?endif;?>
	<p><?= $catalog['f_about'] ?></p>
	
	 <p class="date"><?php setlocale(LC_TIME , 'ru_RU.UTF-8'); print(strftime("%e %B %G", $catalog['f_date']));?></p>

	<p class="clear"></p>
	<br><a href="/news/">Вернуться к списку новостей</a>
	<p class="clear"></p>
</div>