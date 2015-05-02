<?#Список новостей#?>
<div class="news-list-item">
	<?if ($catalog['f_file_data']):?>
	<a href="<?= $APP->getCurUrl ?>?id=<?= $catalog['module_list_item_id'] ?>">
		<img src="/files/resize_cache<?= $catalog['f_file_data']['dirname'] . '200x120-crop_'.$catalog['f_file_data']['name'] ?>" alt="" />
	</a>
	<?endif;?>
	<p class="link"><a href="<?= $APP->getCurUrl ?>?id=<?= $catalog['module_list_item_id'] ?>"><?= $catalog['f_title'] ?></a></p>
	<p class="anons"><?=$catalog['f_description']?></p>
	
 <p class="date"><?php setlocale(LC_TIME , 'ru_RU.UTF-8'); print(strftime("%e %B %G", $catalog['f_date']));?></p>
<p class="clear"></p>
</div>
<p class="clear"></p>