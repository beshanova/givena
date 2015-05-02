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
<h1><?= $catalog['f_title'] ?></h1>
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
	<div class="news-list-item inn-news">
		<p><?= $catalog['f_description'] ?></p>
		<i><?= $catalog['f_username'] ?></i>
		
		 <p class="date"><?php setlocale(LC_TIME , 'ru_RU.UTF-8'); print(strftime("%e %B %G", $catalog['f_date']));?></p>

		<p class="clear"></p>
		<br><a href="/news/">Вернуться к списку отзывов</a>
		<p class="clear"></p>
	</div>
</div>