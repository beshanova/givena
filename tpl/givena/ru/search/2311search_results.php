<?#Шаблон результатов поиска#?>

<h2>Результаты поиска по &quot;<?=$_REQUEST['q']?>&quot;</h2>

<?if (sizeof($data['results'])>0):?>

<?= $data['pager_list'] ?>

	<? foreach ($data['results'] as $catalog) : ?>
		<div class="cat-list-item-price">
		  <input type="hidden" id="bu-<?= $catalog['item_id'] ?>" name="bu" value="<?= $catalog['topic_url'] ?>" />
			<div class="foto_up">
			<a href="<?= $catalog['topic_url'] ?>">

			</a>
			</div>
			<div class="foto_c">
			<? if (sizeof($catalog['f_file_data'])>0) : ?>

			<a href="<?= $catalog['topic_url'] ?>">
				<img src="/files/resize_cache<?= $catalog['f_file_data']['dirname'] . '200x160_'.$catalog['f_file_data']['name'] ?>" alt="" />
			</a>
			<? else : ?>

			<a href="<?= $catalog['topic_url'] ?>">
				<img src="/tpl/givena/images/nofoto.jpg" alt="" />
			</a>

			<?endif;?>
			</div>
			<h3 style="margin-top: 10px;"><a href="<?= $catalog['topic_url'] ?>"><?= $catalog['f_title'] ?></a></h3>
			<p class="descr"><?= $catalog['f_text'] ?></p>

		    <div class="to-basket-block">
<? if (($catalog['f_availability'])=='Нет в наличии') : ?>
				<img src="/tpl/givena/images/add-to-button-2.png" alt="" >
			<? else : ?>

			<? if (count($catalog['price_mass'])==1):?>

				<input class="add-to-button" value="" type="button" onclick="return Add2Basket(<?=$catalog['item_id'];?>, 'Catalog', this, <?=current(array_flip($catalog['price_mass']))+1; reset($catalog['price_mass']); ?>);">

			<? elseif (count($catalog['price_mass'])>1):?>

					  <input class="add-to-button" id="add-to-button-<?= $catalog['item_id'] ?>" value="" type="button" onclick="return Add2Basket(<?=$catalog['item_id'];?>, 'Catalog', this, 0);">

					<div class="root-choice" id="root-choice-<?= $catalog['item_id'] ?>" style="display: none;">
						<img src="/tpl/givena/images/root.png" alt="" />
						<h3>Сделайте пожалуйста выбор</h3>

						<? if ($catalog['f_p_act'] && $catalog['f_p_see']) : ?>
							<div onclick="return Add2Basket(<?=$catalog['item_id'];?>, 'Catalog', '#add-to-button-<?= $catalog['item_id'] ?>', 1);">
								<p class="price"><?=$catalog['f_price'];?> руб.</p>
								<p class="root-descr"><span><?= PR1 ?></span><br>
								(поставка апрель - май)></p>
								<p class="clear"></p>
							</div>
						<? endif;?>
						<? if ($catalog['f_p2_act'] && $catalog['f_p2_see']) : ?>
							<div onclick="return Add2Basket(<?=$catalog['item_id'];?>, 'Catalog', '#add-to-button-<?= $catalog['item_id'] ?>', 2);">
								<p class="price"><?=$catalog['f_price2'];?> руб.</p>
								<p class="root-descr"><span><?= PR2 ?></span><br>
								(поставка апрель - май)></p>
								<p class="clear"></p>
							</div>
						<? endif;?>
                        <? if ($catalog['f_p4_act'] && $catalog['f_p4_see']) : ?>
							<div onclick="return Add2Basket(<?=$catalog['item_id'];?>, 'Catalog', '#add-to-button-<?= $catalog['item_id'] ?>', 4);">
								<p class="price"><?=$catalog['f_price4'];?> руб.</p>
								<p class="root-descr"><span><?= PR4 ?></span><br>
                                (поставка май - октябрь)></p>
								<p class="clear"></p>
							</div>
						<? endif;?>
						<? if ($catalog['f_p3_act'] && $catalog['f_p3_see']) : ?>
							<div onclick="return Add2Basket(<?=$catalog['item_id'];?>, 'Catalog', '#add-to-button-<?= $catalog['item_id'] ?>', 3);">
								<p class="price"><?=$catalog['f_price3'];?> руб.</p>
								<p class="root-descr"><span><?= PR3 ?></span><!--br>
								(поставка апрель - май)--></p>
								<p class="clear"></p>
							</div>
						<? endif;?>
						
					</div>
				<?endif;?>
			<?endif;?>
			</div>
		  <? if (count($catalog['price_mass'])>0):?>
			<p class="price"><?= min_plus($catalog['price_mass']); ?> руб.</p></form>
		  <? endif; ?>
		</div>

<? endforeach ; ?>

<?= $data['pager_list'] ?>

<? else : ?>

<p>По данному запросу ничего не найдено!</p>

<? endif ; ?>


<!-- УБРАТЬ В СТИЛИ -->
<style>
span.searched-text{
  font-weight:bold;
  color:red;
}
</style>