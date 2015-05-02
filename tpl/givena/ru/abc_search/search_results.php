<?#Шаблон результатов поиска#?>
<?=$abc_content;?>
<h2>Результаты поиска:</h2>
<?if (sizeof($data['results'])>0):?>
<?= $data['pager_list'] ?>
	<? foreach ($data['results'] as $catalog) : ?>
        <?
        $koef = 1; $sale = false;
        if ($catalog['f_sale']){
            $koef = 1 - intval($catalog['f_sale'])/100;
            $sale = true;
        }
        ?>
		<div class="cat-list-item-price">
		  <input type="hidden" id="bu-<?= $catalog['module_list_item_id'] ?>" name="bu" value="/catalog/?id=<?= $catalog['module_list_item_id'] ?>" />
			<div class="foto_up">
			<a href="/catalog/?id=<?= $catalog['module_list_item_id'] ?>">

			</a>
			</div>
			<div class="foto_c">
			<? if (sizeof($catalog['f_file_data'])>0) : ?>

			<a href="/catalog/?id=<?= $catalog['module_list_item_id'] ?>">
				<img src="/files/resize_cache<?= $catalog['f_file_data']['dirname'] . '200x160_'.$catalog['f_file_data']['name'] ?>" alt="" />
			</a>
			<? else : ?>

			<a href="/catalog/?id=<?= $catalog['module_list_item_id'] ?>">
				<img src="/tpl/givena/images/nofoto.jpg" alt="" />
			</a>

			<?endif;?>
			</div>
			<h3 style="margin-top: 10px;"><a href="/catalog/?id=<?= $catalog['module_list_item_id'] ?>"><?= $catalog['f_title'] ?></a></h3>
			<p class="descr"><?= $catalog['f_text'] ?></p>

		    <div class="to-basket-block">
			<? if (($catalog['f_availability'])=='Нет в наличии') : ?>
				<img src="/tpl/givena/images/add-to-button-2.png" alt="" style="float: right;">
			<? else : ?>
			<? if (count($catalog['price_mass'])==1):?>

				<input class="add-to-button" value="" type="button" onclick="return Add2Basket(<?=$catalog['module_list_item_id'];?>, 'Catalog', this, <?=current(array_flip($catalog['price_mass']))+1; reset($catalog['price_mass']); ?>);">

			<? elseif (count($catalog['price_mass'])>1):?>

					  <input class="add-to-button" id="add-to-button-<?= $catalog['module_list_item_id'] ?>" value="" type="button" onclick="return Add2Basket(<?=$catalog['module_list_item_id'];?>, 'Catalog', this, 0);">

					<div class="root-choice" id="root-choice-<?= $catalog['module_list_item_id'] ?>" style="display: none;">
						<img src="/tpl/givena/images/root.png" alt="" />
						<h3>Сделайте пожалуйста выбор</h3>

						<? if ($catalog['f_p_act'] && $catalog['f_p_see']) : ?>
							<div onclick="return Add2Basket(<?=$catalog['module_list_item_id'];?>, 'Catalog', '#add-to-button-<?= $catalog['module_list_item_id'] ?>', 1);">
                                <p class="price<?if ($sale):?> crossline<?endif;?>"><?=$catalog['f_price'];?> руб.</p>
                                <?if($sale):?><p class="price newprice"><?=$catalog['f_price']*$koef;?>руб.</p><?endif;?>
								<p class="root-descr"><span><?= PR1 ?></span><br>
								(поставка апрель - май)></p>
								<p class="clear"></p>
							</div>
						<? endif;?>
						<? if ($catalog['f_p2_act'] && $catalog['f_p2_see']) : ?>
							<div onclick="return Add2Basket(<?=$catalog['module_list_item_id'];?>, 'Catalog', '#add-to-button-<?= $catalog['module_list_item_id'] ?>', 2);">
                                <p class="price<?if ($sale):?> crossline<?endif;?>"><?=$catalog['f_price2'];?> руб.</p>
                                <?if($sale):?><p class="price newprice"><?=$catalog['f_price2']*$koef;?>руб.</p><?endif;?>
								<p class="root-descr"><span><?= PR2 ?></span><br>
								(поставка апрель - май)></p>
								<p class="clear"></p>
							</div>
						<? endif;?>
						<? if ($catalog['f_p4_act'] && $catalog['f_p4_see']) : ?>
							<div onclick="return Add2Basket(<?=$catalog['module_list_item_id'];?>, 'Catalog', '#add-to-button-<?= $catalog['module_list_item_id'] ?>', 4);">
                                <p class="price<?if ($sale):?> crossline<?endif;?>"><?=$catalog['f_price4'];?> руб.</p>
                                <?if($sale):?><p class="price newprice"><?=$catalog['f_price4']*$koef;?>руб.</p><?endif;?>
								<p class="root-descr"><span><?= PR4 ?></span><br>
                                (поставка май - октябрь)></p>
								<p class="clear"></p>
							</div>
						<? endif;?>
						<? if ($catalog['f_p3_act'] && $catalog['f_p3_see']) : ?>
							<div onclick="return Add2Basket(<?=$catalog['module_list_item_id'];?>, 'Catalog', '#add-to-button-<?= $catalog['module_list_item_id'] ?>', 3);">
                                <p class="price<?if ($sale):?> crossline<?endif;?>"><?=$catalog['f_price4'];?> руб.</p>
                                <?if($sale):?><p class="price newprice"><?=$catalog['f_price4']*$koef;?>руб.</p><?endif;?>
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
                <?$min_price = min_plus($catalog['price_mass']);?>

                <p class="price<?if ($sale):?> crossline<?endif;?>"><?=$min_price;?>руб.</p>
                <?if($sale):?><p class="price newprice"><?=$min_price*$koef;?>руб.</p><?endif;?>
                </form>
            <? endif; ?>

            <?if ($catalog['f_sale']):?><div class="sale"><div>-<?=intval($catalog['f_sale']);?>%</div></div><?endif;?>
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