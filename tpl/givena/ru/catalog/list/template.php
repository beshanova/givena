<?#Список каталога#?>
<?
    $koef = 1; $sale = false;
    if ($catalog['f_sale']){
        $koef = 1 - intval($catalog['f_sale'])/100;
        $sale = true;
    }
?>
<div class="cat-list-item-price">
  <input type="hidden" id="bu-<?= $catalog['module_list_item_id'] ?>" name="bu" value="<?= $APP->getCurUrl() ?>?id=<?= $catalog['module_list_item_id'] ?>" />
	<div class="foto_up">
	<a href="<?= $APP->getCurUrl ?>?id=<?= $catalog['module_list_item_id'] ?>">

	</a>
	</div>
	<div class="foto_c">
	<? if (sizeof($catalog['f_file_data'])>0) : ?>

	<a href="<?= $APP->getCurUrl ?>?id=<?= $catalog['module_list_item_id'] ?>">
		<img src="/files/resize_cache<?= $catalog['f_file_data']['dirname'] . '200x160_'.$catalog['f_file_data']['name'] ?>" alt="" />
	</a>
	<? else : ?>

	<a href="<?= $APP->getCurUrl ?>?id=<?= $catalog['module_list_item_id'] ?>">
		<img src="/tpl/givena/images/nofoto.jpg" alt="" />
	</a>

	<?endif;?>
	</div>
	<div class="name_h3" style="margin-top: 10px;"><h3><a href="<?= $APP->getCurUrl ?>?id=<?= $catalog['module_list_item_id'] ?>"><?= $catalog['f_title'] ?></a></h3></div>
	<p class="descr"><?= $catalog['f_text'] ?></p>

    <div class="to-basket-block">
      <? if (($catalog['f_availability'])=='Нет в наличии'||count($price_mass)==0) : ?>
		<img src="/tpl/givena/images/add-to-button-2.png" alt="" style="float: right;">
	  <? else : ?>
    	<? if (count($price_mass)==1):?>

    		<input class="add-to-button" value="" type="button" onclick="return Add2Basket(<?=$catalog['module_list_item_id'];?>, '<?= $this->getClassName() ?>', this, <?=current(array_flip($price_mass))+1; reset($price_mass); ?>);">

    	<? elseif (count($price_mass)>1):?>

			  <input class="add-to-button" id="add-to-button-<?= $catalog['module_list_item_id'] ?>" value="" type="button" onclick="return Add2Basket(<?=$catalog['module_list_item_id'];?>, '<?= $this->getClassName() ?>', this, 0);">

			<div class="root-choice" id="root-choice-<?= $catalog['module_list_item_id'] ?>" style="display: none;">
				<img src="/tpl/givena/images/root.png" alt="" />
				<h3>Сделайте пожалуйста выбор</h3>

				<? if ($catalog['f_p_act'] && $catalog['f_p_see']) : ?>
					<div onclick="return Add2Basket(<?=$catalog['module_list_item_id'];?>, '<?= $this->getClassName() ?>', '#add-to-button-<?= $catalog['module_list_item_id'] ?>', 1);">
						<p class="price<?if ($sale):?> crossline<?endif;?>"><?=$catalog['f_price'];?> руб.</p>
                        <?if($sale):?><p class="price newprice"><?=$catalog['f_price']*$koef;?>руб.</p><?endif;?>
						<p class="root-descr"><span><?= PR1 ?></span><br>
						(поставка апрель - май)></p>
						<p class="clear"></p>
					</div>
				<? endif;?>
				<? if ($catalog['f_p2_act'] && $catalog['f_p2_see']) : ?>
					<div onclick="return Add2Basket(<?=$catalog['module_list_item_id'];?>, '<?= $this->getClassName() ?>', '#add-to-button-<?= $catalog['module_list_item_id'] ?>', 2);">
						<p class="price<?if ($sale):?> crossline<?endif;?>"><?=$catalog['f_price2'];?> руб.</p>
                        <?if($sale):?><p class="price newprice"><?=$catalog['f_price2']*$koef;?>руб.</p><?endif;?>
						<p class="root-descr"><span><?= PR2 ?></span><br>
						(поставка апрель - май)></p>
						<p class="clear"></p>
					</div>
				<? endif;?>
				<? if ($catalog['f_p4_act'] && $catalog['f_p4_see']) : ?>
					<div onclick="return Add2Basket(<?=$catalog['module_list_item_id'];?>, '<?= $this->getClassName() ?>', '#add-to-button-<?= $catalog['module_list_item_id'] ?>', 4);">
						<p class="price<?if ($sale):?> crossline<?endif;?>"><?=$catalog['f_price4'];?> руб.</p>
                        <?if($sale):?><p class="price newprice"><?=$catalog['f_price4']*$koef;?>руб.</p><?endif;?>
						<p class="root-descr"><span><?= PR4 ?></span><br>
						(поставка май-октябрь)></p>
						<p class="clear"></p>
					</div>
				<? endif;?>
				<? if ($catalog['f_p3_act'] && $catalog['f_p3_see']) : ?>
					<div onclick="return Add2Basket(<?=$catalog['module_list_item_id'];?>, '<?= $this->getClassName() ?>', '#add-to-button-<?= $catalog['module_list_item_id'] ?>', 3);">
						<p class="price<?if ($sale):?> crossline<?endif;?>"><?=$catalog['f_price3'];?> руб.</p>
                        <?if($sale):?><p class="price newprice"><?=$catalog['f_price3']*$koef;?>руб.</p><?endif;?>
						<p class="root-descr"><span><?= PR3 ?></span><!--br>
						(поставка апрель - май)--></p>
						<p class="clear"></p>
					</div>
				<? endif;?>
			</div>
		<?endif;?>
	  <?endif;?>
	</div>
  <? if (sizeof($price_mass)>0):?>
      <?
      $min_price = min_plus($price_mass);
      ?>
        <p class="price<?if ($sale):?> crossline<?endif;?>"><?=$min_price;?>руб.</p>
        <?if($sale):?><p class="price newprice"><?=$min_price*$koef;?>руб.</p><?endif;?>
      </form>
  <? endif; ?>

    <?if ($catalog['f_sale']):?><div class="sale"><div>-<?=intval($catalog['f_sale']);?>%</div></div><?endif;?>
</div>
<style>
.cont-text .admin-div-block-border .admin-div-block-border {
    float: left;
    margin: 0 27px 20px 0;
    width: 210px;
}
.cont-text .admin-div-block-border .admin-div-block-border-no-active {
    float: left;
    margin: 0 27px 20px 0;
    width: 210px;
}
    
</style>