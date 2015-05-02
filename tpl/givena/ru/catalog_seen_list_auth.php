<br><br>

<div class="recommend">

	<h2>Просмотренные товары</h2>

<? if ($catalog) : ?>

	<? foreach($catalog as $n) : ?>
        <?
        $koef = 1; $sale = false;
        if ($n['f_sale']){
            $koef = 1 - intval($n['f_sale'])/100;
            $sale = true;
        }
        ?>

				<div class="rec-item">
				<div class="foto_up">
				<a href="<?= $n['item_url'] ?>?id=<?= $n['module_list_item_id'] ?>">

					</a>
				</div>
				<? if (sizeof($n['f_file_data'])>0) : ?>

					<a href="<?= $n['item_url'] ?>?id=<?= $n['module_list_item_id'] ?>">
						<img src="/files/resize_cache<?= $n['f_file_data']['dirname'] . '200x140-crop_'.$n['f_file_data']['name'] ?>" alt="" />
					</a>
					<? else : ?>

					<a href="<?= $n['item_url'] ?>?id=<?= $n['module_list_item_id'] ?>">
						<img src="/tpl/givena/images/nofoto.jpg" alt="" />
					</a>

				<?endif;?>

				<h3><a href="<?= $n['item_url'] ?>?id=<?= $n['module_list_item_id'] ?>"><?= $n['f_title'] ?></a></h3>
				<p class="descr"><?= substr($n['f_text'], 0, 125);?></p>
				<form method="post" action="">
				  <input type="hidden" style="display:none;" name="item_id" value="<?= $n['module_list_item_id'] ?>">
				  <input type="hidden" style="display:none;" name="action" value="add2basket">
				  <input type="hidden" style="display:none;" name="tm" value="<?= $this->getClassName() ?>">
				  <input type="hidden" style="display:none;" name="bu" value="<?= urlencode($_SERVER['REQUEST_URI']) ?>" id="bu-00-<?= $n['module_list_item_id'] ?>">

				  <input type="text" maxlength="3" id="counter<?= $n['module_list_item_id'] ?>" name="cnt" value="1" style="display:none;">

          <? if (count($n['price_mass'])==1) : ?>
<!--?=printarray(array_filter(array($n['f_price'],$n['f_price2'],$n['f_price3'],$n['f_price4'])));?-->
<?
//    $cur = each(array_filter(array($n['f_price'],$n['f_price2'],$n['f_price3'],$n['f_price4'])));

?>
          <input id="add2-to-button-<?= $n['module_list_item_id'] ?>" class="add2-to-button" value="" type="button" onclick="return Add2Basket(<?=$n['module_list_item_id'];?>, 'Catalog', this, <?=current(array_flip($n['price_mass']))+1; reset($n['price_mass']); ?>);">

          <? else : ?>

          <div class="submit to-basket-block">
			<? if (($n['f_availability'])=='Нет в наличии'||sizeof($n['price_mass'])==0) : ?>
				<img src="/tpl/givena/images/add-to-button-2.png" alt="" style="float: right;">
			<? else : ?>
					  <input id="add2-to-button-<?= $n['module_list_item_id'] ?>" type="submit" border="0" onclick="return Add2Basket(<?= $n['module_list_item_id'] ?>, 'Catalog', this, 0);" value="" class="add-to-button">



					   <div class="root-choice" id="root-choice-<?= $n['module_list_item_id'] ?>" style="display: none;">
					   <img src="/tpl/givena/images/root-1.png" class="root-1" alt="">
            <h3>Сделайте пожалуйста выбор</h3>

            <? if ($n['f_p_act'] && $n['f_p_see']) : ?>
              <div onclick="return Add2Basket(<?=$n['module_list_item_id'];?>, 'Catalog', '#add2-to-button-<?= $n['module_list_item_id'] ?>', 1);">
                <p class="price bottom-price<?if ($sale):?> crossline<?endif;?>"><?=$n['f_price'];?> руб.</p>
                <?if($sale):?><p class="price bottom-price newprice"><?=$n['f_price']*$koef;?>руб.</p><?endif;?>
                <p class="root-descr"><span><?= PR1 ?></span><br>
                (поставка апрель - май)></p>
                <p class="clear"></p>
              </div>
            <? endif;?>
            <? if ($n['f_p2_act'] && $n['f_p2_see']) : ?>
              <div onclick="return Add2Basket(<?=$n['module_list_item_id'];?>, 'Catalog', '#add2-to-button-<?= $n['module_list_item_id'] ?>', 2);">
                <p class="price bottom-price<?if ($sale):?> crossline<?endif;?>"><?=$n['f_price2'];?> руб.</p>
                <?if($sale):?><p class="price bottom-price newprice"><?=$n['f_price2']*$koef;?>руб.</p><?endif;?>
                <p class="root-descr"><span><?= PR2 ?></span><br>
                (поставка апрель - май)></p>
                <p class="clear"></p>
              </div>
            <? endif;?>
            <? if ($n['f_p4_act'] && $n['f_p4_see']) : ?>
              <div onclick="return Add2Basket(<?=$n['module_list_item_id'];?>, 'Catalog', '#add2-to-button-<?= $n['module_list_item_id'] ?>', 4);">
                <p class="price bottom-price<?if ($sale):?> crossline<?endif;?>"><?=$n['f_price4'];?> руб.</p>
                <?if($sale):?><p class="price bottom-price newprice"><?=$n['f_price4']*$koef;?>руб.</p><?endif;?>
                <p class="root-descr"><span><?= PR4 ?></span><br>
				(поставка май-октябрь)></p>
                <p class="clear"></p>
              </div>
            <? endif;?>
            <? if ($n['f_p3_act'] && $n['f_p3_see']) : ?>
              <div onclick="return Add2Basket(<?=$n['module_list_item_id'];?>, 'Catalog', '#add2-to-button-<?= $n['module_list_item_id'] ?>', 3);">
                <p class="price bottom-price<?if ($sale):?> crossline<?endif;?>"><?=$n['f_price3'];?> руб.</p>
                <?if($sale):?><p class="price bottom-price newprice"><?=$n['f_price3']*$koef;?>руб.</p><?endif;?>
                <p class="root-descr"><span><?= PR3 ?></span><!--br>
                (поставка апрель - май)--></p>
                <p class="clear"></p>
              </div>
            <? endif;?>
          </div>


          <?endif;?>
				  </div>


          <? endif ; ?>

        <? if (sizeof($n['price_mass'])>0):?>

                    <?$min_price = min_plus($n['price_mass']);?>

                    <p class="price<?if ($sale):?> crossline<?endif;?>"><?=$min_price;?>руб.</p>
                    <?if($sale):?><p class="price newprice"><?=$min_price*$koef;?>руб.</p><?endif;?>

        <? endif; ?>

				</form>

                 <?if ($n['f_sale']):?><div class="sale"><div>-<?=intval($n['f_sale']);?>%</div></div><?endif;?>
			</div>

	<? endforeach ; ?>
<p class="clear"></p>

<? else : ?>
<p>Нет просмотренных Вами товаров!</p>
<? endif ; ?>

</div>
