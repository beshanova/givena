<div class="reviews-main">	
	<h3>Фото-отзывы клиентов</h3>
	<div>
	   <? 
		$numItems = count($catalog);
		$i = 0;
		foreach ($catalog as $n) : ?>
		<div class="reviews-item">
			<b><?= $n['f_username'] ?></b>
			<a href="/<?= $data['module_menu_url'] ?>/?id=<?= $n['module_list_item_id'] ?>">
				<img src="/files/resize_cache<?= $n['f_file_data']['dirname'] . '200x120-crop_'.$n['f_file_data']['name'] ?>" alt="" />
			</a>
			<p class="anons"><?=$n['f_title'];?></p>		
		</div>
		<?
		if(++$i != $numItems) {
			echo "<div class='reviews_space'><img class='twirl' alt='' src='/tpl/givena/images/twirl.gif'></div>";
		}
		?>
		
	  <? endforeach ; ?>
	  <p class="clear"></p>
	</div>
	<a href="/<?= $data['module_menu_url'] ?>/" class="allreviews">Посмотреть все</a>
</div>