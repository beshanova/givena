<?#Информация о странице#?>

<?if ($catalog['page_name']):?>
	<h1><?=$data['page_name']?></h1>
<?endif;?>

<? if ($data['page_img']):?>
<div class="cat-inner-img">
  <img src="/files/resize_cache<?= $data['page_img_data']['dirname'] . '250x200-crop_'.$data['page_img_data']['name'] ?>" alt="" />
</div>
<?endif;?>

<?=$data['page_desc']?>
<p class="clear"></p>