<div class="recommend">
	<h2>Рекомендуемые товары</h2>
	<? foreach ($catalog as $n) {?>
		<? if ($n[f_checkbox]==1) {?>
				<div class="rec-item">
				<div class="foto_up">
				<a href="/catalog/<?= $APP->getCurUrl ?>?id=<?= $n['module_list_item_id'] ?>">
						
					</a>
				</div>
				<? if (sizeof($n['f_file_data'])>0) : ?>

					<a href="/catalog/<?= $APP->getCurUrl ?>?id=<?= $n['module_list_item_id'] ?>">
						<img src="/files/resize_cache<?= $n['f_file_data']['dirname'] . '200x120-crop_'.$n['f_file_data']['name'] ?>" alt="" />
					</a>
					<? else : ?>

					<a href="/catalog/<?= $APP->getCurUrl ?>?id=<?= $n['module_list_item_id'] ?>">
						<img src="/tpl/givena/images/nofoto.jpg" alt="" />
					</a>
				
				<?endif;?>

				<h3><a href="/catalog/<?= $APP->getCurUrl ?>?id=<?= $n['module_list_item_id'] ?>"><?= $n['f_title'] ?></a></h3>
				<p class="descr"><?= substr($n['f_text'], 0, 125);?></p>
				<form method="post" action="">
				  <input type="hidden" style="display:none;" name="item_id" value="<?= $n['module_list_item_id'] ?>">
				  <input type="hidden" style="display:none;" name="action" value="add2basket">
				  <input type="hidden" style="display:none;" name="tm" value="<?= $this->getClassName() ?>">
				  <input type="hidden" style="display:none;" name="bu" value="<?= urlencode($_SERVER['REQUEST_URI']) ?>" id="bu-00-<?= $n['module_list_item_id'] ?>">

				  
				  <input type="text" maxlength="3" id="counter<?= $n['module_list_item_id'] ?>" name="cnt" value="1" style="display:none;">
				  <div class="submit" onClick="alert('Товар добавлен в корзину')">
					<input type="submit" border="0" onclick="return Add2Basket(<?= $n['module_list_item_id'] ?>, 'catalog');" value="" class="add-to-button">
				  </div>
				</form>
				<p class="price"><?= $n['f_price'] ?> руб.</p>
				
			</div>
		<? }; ?>
	<? }; ?>
			

<p class="clear"></p>
</div>
