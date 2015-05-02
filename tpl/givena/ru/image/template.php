<?#Картинка#?>
<? if ($image['module_image_is_popup']) : ?>
	<script type="text/javascript" src="/tpl/givena/js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
	<link rel="stylesheet" type="text/css" href="/tpl/givena/js/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
	<script type="text/javascript">
		$(document).ready(function() {
			$("a[rel=example_group]").fancybox({
					'transitionIn'		: 'none',
					'transitionOut'		: 'none',
					'titlePosition' 	: 'over',
					'titleFormat'		: function(title, currentArray, currentIndex, currentOpts) {
						return '<span id="fancybox-title-over">Image ' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>';
					}
				});
		});
	</script>
<? endif ; ?>

<? if ($image['module_image_title']) : ?>
  <? if ($image['module_image_link']) : ?>
	  <!--<a href="<?= $image['module_image_link'] ?>"><b><?= $image['module_image_title'] ?></b></a>-->
  <? else : ?>
	  <!--<b><?= $image['module_image_title'] ?></b>-->
  <? endif; ?>
<? endif ; ?>
<?
if ($image['module_image_target']=="left") $styleimg="float: left; margin: 0 10px 10px 0;";
elseif ($image['module_image_target']=="center") $styleimg="text-align: center; margin: 0 0 0 10px;";
elseif ($image['module_image_target']=="right") $styleimg="float: right; margin: 0 10px 0 10px;";
?>

<p style="<?= $styleimg?>">

<? if ($image['module_image_is_popup']) : ?>
	<a rel="example_group" href="/files/resize_cache<?= $image['module_image_src_data']['dirname'] . '759x722_'.$image['module_image_src_data']['name'] ?>">
		<img src="/files/resize_cache<?= $image['module_image_src_data']['dirname'] . '410x390_'.$image['module_image_src_data']['name'] ?>" alt="" style="border:0;"/>
	</a>
<? else: ?>
	<img src="/files/resize_cache<?= $image['module_image_src_data']['dirname'] . '410x390_'.$image['module_image_src_data']['name'] ?>" alt="" style="border:0;"/>
<? endif ; ?>

</p>
