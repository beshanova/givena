<?#Позиция каталога#?>
<div class="catalog_item_detail">

<input type="hidden" id="bu-<?= $catalog['module_list_item_id'] ?>" name="bu" value="<?= $_SERVER['REQUEST_URI'] ?>" />

<script type="text/javascript" src="/tpl/givena/js/thickbox.js"></script>
<link rel="stylesheet" type="text/css" href="/tpl/givena/css/thickbox.css" media="screen" />


<script type="text/javascript">
  $(document).ready(function(){
    $('.ad-gallery').adGallery({
      callbacks:{
        beforeImageVisible: function(){
          $("#gallery").unbind('click');
          var img = $("#gallery div.ad-image span.fancybox").attr('href');
          $("#gallery").click(function() {
            tb_show('', img, 'images');
          });
        }
      }
    });
  });
</script>

<h1><?= $catalog['f_title'] ?><?if ($catalog['f_lat']):?><small> (<?= $catalog['f_lat'] ?>)</small><?endif;?></h1>

<div class="slider-inner">
	<div id="gallery" class="ad-gallery">
		<div class="foto_up"></div>
		<div class="ad-image-wrapper">
		</div>
		<div class="ad-controls">
		</div>
		<div class="ad-nav">
			<div class="ad-thumbs">
				<ul class="ad-thumb-list">

				<li>

					<? if (sizeof($catalog['f_file_data'])>0) : ?>
					<a href="/files/resize_cache<?= $catalog['f_file_data']['dirname'] . '318x257_'.$catalog['f_file_data']['name'] ?>">
						<div class="foto_up"></div>
						<img src="/files/resize_cache<?= $catalog['f_file_data']['dirname'] . '86x50-crop_'.$catalog['f_file_data']['name'] ?>"  longdesc="/files/resize_cache<?= $catalog['f_file_data']['dirname'] . '500x700_'.$catalog['f_file_data']['name'] ?>" alt=""/>
					</a>

					<? else : ?>
					<a href="/tpl/givena/images/nofoto_318-227.jpg"  longdesc="/tpl/givena/images/nofoto_318-227.jpg" alt=""/>
						<img src="/tpl/givena/images/nofoto_86-50.jpg" alt="" />
					</a>
					<?endif;?>
				</li>
				<?if ($catalog['f_file2_data']):?>
				<li>

					<a href="/files/resize_cache<?= $catalog['f_file2_data']['dirname'] . '318x257_'.$catalog['f_file2_data']['name'] ?>">
						<div class="foto_up"></div>
						<img src="/files/resize_cache<?= $catalog['f_file2_data']['dirname'] . '86x50-crop_'.$catalog['f_file2_data']['name'] ?>"  longdesc="/files/resize_cache<?= $catalog['f_file2_data']['dirname'] . '500x700_'.$catalog['f_file2_data']['name'] ?>" alt=""/>
					</a>
				</li>
				<?endif;?>
				<?if ($catalog['f_file3_data']):?>
				<li>

					<a href="/files/resize_cache<?= $catalog['f_file3_data']['dirname'] . '318x257_'.$catalog['f_file3_data']['name'] ?>">
						<div class="foto_up"></div>
						<img src="/files/resize_cache<?= $catalog['f_file3_data']['dirname'] . '86x50-crop_'.$catalog['f_file3_data']['name'] ?>"  longdesc="/files/resize_cache<?= $catalog['f_file3_data']['dirname'] . '500x700_'.$catalog['f_file3_data']['name'] ?>" alt=""/>
					</a>
				</li>
				<?endif;?>
				<?if ($catalog['f_file4_data']):?>
				<li>

					<a href="/files/resize_cache<?= $catalog['f_file4_data']['dirname'] . '318x257_'.$catalog['f_file4_data']['name'] ?>">
					<div class="foto_up"></div>
						<img src="/files/resize_cache<?= $catalog['f_file4_data']['dirname'] . '86x50-crop_'.$catalog['f_file4_data']['name'] ?>"  longdesc="/files/resize_cache<?= $catalog['f_file4_data']['dirname'] . '500x700_'.$catalog['f_file4_data']['name'] ?>" alt=""/>
					</a>
				</li>
				<?endif;?>
				<?if ($catalog['f_file5_data']):?>
				<li>

					<a href="/files/resize_cache<?= $catalog['f_file5_data']['dirname'] . '318x257_'.$catalog['f_file5_data']['name'] ?>">
					<div class="foto_up"></div>
						<img src="/files/resize_cache<?= $catalog['f_file5_data']['dirname'] . '86x50-crop_'.$catalog['f_file5_data']['name'] ?>"  longdesc="/files/resize_cache<?= $catalog['f_file5_data']['dirname'] . '500x700_'.$catalog['f_file5_data']['name'] ?>" alt=""/>
					</a>
				</li>
				<?endif;?>
				<?if ($catalog['f_file6_data']):?>
				<li>

					<a href="/files/resize_cache<?= $catalog['f_file6_data']['dirname'] . '318x257_'.$catalog['f_file6_data']['name'] ?>">
					<div class="foto_up"></div>
						<img src="/files/resize_cache<?= $catalog['f_file6_data']['dirname'] . '86x50-crop_'.$catalog['f_file6_data']['name'] ?>"  longdesc="/files/resize_cache<?= $catalog['f_file6_data']['dirname'] . '500x700_'.$catalog['f_file6_data']['name'] ?>" alt=""/>
					</a>
				</li>
				<?endif;?>
				</ul>
			</div>
		</div>
	</div>
</div>

<div class="product-descr">
	<table>
		<?if ($catalog['f_author']||$catalog['f_city']||$catalog['f_year']):?>
		<tr>
			<th style="padding-top:0;">Оригинатор</th><td style="padding-top:0;"><?= $catalog['f_author'] ?> / <?= $catalog['f_city'] ?> / <?= $catalog['f_year'] ?></td>
		</tr>
		<?endif;?>
		<?if ($catalog['f_group']):?>
		<tr>
			<th>Группа</th><td><?= $catalog['f_group'] ?></td>
		</tr>
		<?endif;?>
		<?if ($catalog['f_height']):?>
		<tr>
			<th>Высота растения, см</th><td><?= $catalog['f_height'] ?></td>
		</tr>
		<?endif;?>
		<?if ($catalog['f_periodbloom']):?>
		<tr>
			<th>Период цветения</th><td><?= $catalog['f_periodbloom'] ?></td>
		</tr>
		<?endif;?>
		<?if ($catalog['f_diameter']):?>
		<tr>
			<th>Диаметр цветка, см</th><td><?= $catalog['f_diameter'] ?></td>
		</tr>
		<?endif;?>
		<?if ($catalog['f_color']):?>
		<tr>
			<th>Окраска цветка</th>
			<td><?= $catalog['f_color'] ?></td>
		</tr>
		<?endif;?>
		<?if ($catalog['f_aroma']):?>
		<tr>
			<th>Аромат</th><td><img src="/tpl/givena/images/clover-<?= $catalog['f_aroma'] ?>.png" alt="" /></td>
		</tr>
		<?endif;?>
		<?if ($catalog['f_diseases']):?>
		<tr>
			<th>Устойчивость к болезням</th><td><img src="/tpl/givena/images/clover-<?= $catalog['f_diseases'] ?>.png" alt="" /></td>
		</tr>
		<?endif;?>
		<?if ($catalog['f_rain']):?>
		<tr>
			<th>Устойчивость к дождю</th><td><img src="/tpl/givena/images/clover-<?= $catalog['f_rain'] ?>.png" alt="" /></td>
		</tr>
		<?endif;?>
		<?if ($catalog['f_frost']):?>
		<tr>
			<th>Морозостойкость</th><td><?= $catalog['f_frost'] ?></td>
		</tr>
		<?endif;?>

	</table>
</div>
<div>

</div>
<p class="clear"></p><br>
<p><?= $catalog['f_text'] ?></p><br>
<?if ($catalog['f_p_see']||$catalog['f_p2_see']||$catalog['f_p3_see']||$catalog['f_p4_see']):?>
<h2>Цена</h2>
<?else:?>
<h2>Нет в наличии</h2>
<?endif;?>

<table>
	<?if ($catalog['f_p_see'] && $catalog['f_price']):?>
	<tr>
		<td style="width:460px;">
			<p class="text1"><?= PR1 ?><br>
			<span>(поставка март-апрель)</span></p>
		</td>
		<td style="text-align: right;">
			<p class="price"><?= $catalog['f_price'] ?> руб.</p>
		</td>
		<td style="width: 100px;">
			<div class="to-basket-block">
				<form method="post" action="">
					<input type="hidden" style="display:none;" name="item_id" value="<?= $catalog['module_list_item_id'] ?>"><input type="hidden" style="display:none;" name="action" value="add2basket"><input type="hidden" style="display:none;" name="tm" value="<?= $this->getClassName() ?>"><input type="hidden" style="display:none;" name="bu" value="<?= urlencode($_SERVER['REQUEST_URI']) ?>" id="bu-00-<?= $catalog['module_list_item_id'] ?>">
					<div class="submit" style="    width: 77px;">
						<? if ( ! $catalog['f_p_act'] ) : ?>
								<img src="/tpl/givena/images/add-to-button-2.png" alt="" style="float: right;">
						<? else : ?>
								<input type="submit" border="0" onclick="return Add2Basket(<?= $catalog['module_list_item_id'] ?>, '<?= $this->getClassName() ?>', this, 1);" value="" class="add-to-button ">
						<?endif;?>

					</div>
				</form>
			</div>
		</td>
	</tr>
	<?endif;?>
	<?if ($catalog['f_p2_see'] && $catalog['f_price2']):?>
	<tr>
		<td >
			<p class="text1"><?= PR2 ?><br>
			<span>(поставка апрель-май)</span></p>
		</td>
		<td style="text-align: right;">
			<p class="price"><?= $catalog['f_price2'] ?> руб.</p>
		</td>
		<td style="width: 100px;">
			<div class="to-basket-block">
				<form method="post" action="">
					<input type="hidden" style="display:none;" name="item_id" value="<?= $catalog['module_list_item_id'] ?>"><input type="hidden" style="display:none;" name="action" value= "add2basket"><input type="hidden" style="display:none;" name="tm" value="<?= $this->getClassName() ?>"><input type="hidden" style="display:none;" name="bu" value="<?= urlencode($_SERVER['REQUEST_URI']) ?>" id="bu-00-<?= $catalog['module_list_item_id'] ?>">
					<div class="submit" style="    width: 77px;">
						<? if ( ! $catalog['f_p2_act'] ) : ?>
								<img src="/tpl/givena/images/add-to-button-2.png" alt="" style="float: right;">
						<? else : ?>
						<input type="submit" border="0" onclick="return Add2Basket(<?= $catalog['module_list_item_id'] ?>, '<?= $this->getClassName() ?>', this, 2);" value="" class="add-to-button">
						<?endif;?>
					</div>
				  </form>
			</div>
		</td>
	</tr>
	<?endif;?>
	<?if ($catalog['f_p4_see'] && $catalog['f_price4']):?>
	<tr>
		<td >
			<p class="text1"><?= PR4 ?><br>
            <!--span>(заказ 2014)</span-->
			<span>(поставка май-октябрь)</span>
			</p>
		</td>
		<td style="text-align: right;">
			<p class="price"><?= $catalog['f_price4'] ?> руб.</p>
		</td>
		<td style="width: 100px;">
			<div class="to-basket-block">
				<form method="post" action="">
					<input type="hidden" style="display:none;" name="item_id" value="<?= $catalog['module_list_item_id'] ?>"><input type="hidden" style="display:none;" name="action" value="add2basket"><input type="hidden" style="display:none;" name="tm" value="<?= $this->getClassName() ?>"><input type="hidden" style="display:none;" name="bu" value="<?= urlencode($_SERVER['REQUEST_URI']) ?>" id="bu-00-<?= $catalog['module_list_item_id'] ?>">
					<div class="submit">
            <? if ( ! $catalog['f_p4_act'] ) : ?>
								<img src="/tpl/givena/images/add-to-button-2.png" alt="" style="float: right;">
						<? else : ?>
						  <input type="submit" border="0" onclick="return Add2Basket(<?= $catalog['module_list_item_id'] ?>, '<?= $this->getClassName() ?>', this, 4);" value="" class="add-to-button">
            <? endif ; ?>
					</div>
				  </form>
			</div>
		</td>
	</tr>
	<?endif;?>
	<?if ($catalog['f_p3_see'] && $catalog['f_price3']):?>
	<tr>
		<td >
			<p class="text1"><?= PR3 ?><br>
			<!--span>(поставка апрель-октябрь)</span></p-->
		</td>
		<td style="text-align: right;">
			<p class="price"><?= $catalog['f_price3'] ?> руб.</p>
		</td>
		<td style="width: 100px;">
			<div class="to-basket-block">
				<form method="post" action="">
					<input type="hidden" style="display:none;" name="item_id" value="<?= $catalog['module_list_item_id'] ?>"><input type="hidden" style="display:none;" name="action" value="add2basket"><input type="hidden" style="display:none;" name="tm" value="<?= $this->getClassName() ?>"><input type="hidden" style="display:none;" name="bu" value="<?= urlencode($_SERVER['REQUEST_URI']) ?>" id="bu-00-<?= $catalog['module_list_item_id'] ?>">
					<div class="submit" style="    width: 77px;">
						<? if ( ! $catalog['f_p3_act'] ) : ?>
								<img src="/tpl/givena/images/add-to-button-2.png" alt="" style="float: right;">
						<? else : ?>
						<input type="submit" border="0" onclick="return Add2Basket(<?= $catalog['module_list_item_id'] ?>, '<?= $this->getClassName() ?>', this, 3);" value="" class="add-to-button">
						<?endif;?>
					</div>
				  </form>
			</div>
		</td>
	</tr>
	<?endif;?>
</table>
<br><br>

<br>
<a href="<?= $APP->getCurUrl() ?>">Вернуться в раздел</a>
</div>

<?if ($catalog['f_file_data']):?>
<a style="display:none;" href="/files/resize_cache<?= $catalog['f_file_data']['dirname'] . '500x700_'.$catalog['f_file_data']['name'] ?>" rel="images" class="thickbox"></a>
<?endif;?>
<?if ($catalog['f_file2_data']):?>
<a style="display:none;" href="/files/resize_cache<?= $catalog['f_file2_data']['dirname'] . '500x700_'.$catalog['f_file2_data']['name'] ?>" rel="images" class="thickbox"></a>
<?endif;?>
<?if ($catalog['f_file3_data']):?>
<a style="display:none;" href="/files/resize_cache<?= $catalog['f_file3_data']['dirname'] . '500x700_'.$catalog['f_file3_data']['name'] ?>" rel="images" class="thickbox"></a>
<?endif;?>
<?if ($catalog['f_file4_data']):?>
<a style="display:none;" href="/files/resize_cache<?= $catalog['f_file4_data']['dirname'] . '500x700_'.$catalog['f_file4_data']['name'] ?>" rel="images" class="thickbox"></a>
<?endif;?>
<?if ($catalog['f_file5_data']):?>
<a style="display:none;" href="/files/resize_cache<?= $catalog['f_file5_data']['dirname'] . '500x700_'.$catalog['f_file5_data']['name'] ?>" rel="images" class="thickbox"></a>
<?endif;?>
<?if ($catalog['f_file6_data']):?>
<a style="display:none;" href="/files/resize_cache<?= $catalog['f_file6_data']['dirname'] . '500x700_'.$catalog['f_file6_data']['name'] ?>" rel="images" class="thickbox"></a>
<?endif;?>