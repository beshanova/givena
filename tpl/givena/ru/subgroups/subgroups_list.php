<?#Список подразделов страницы#?>
<div class="subgroups_list">
<? foreach ($data['list'] as $sub) : ?>
	<div class="cat-list-item">
		<div class="foto_c">
		<? if (strlen($sub['detail']['page_img'])>1) : ?>
				<a href="/<?=$sub['urls']?>/"><img src="/files/resize_cache<?= $sub['detail']['page_img_data']['dirname'] . '200x160_'.$sub['detail']['page_img_data']['name'] ?>" alt="" /></a>
		<? else : ?>
				<a href="/<?=$sub['urls']?>/"><img src="/tpl/givena/images/nofoto.jpg" alt="" /></a>
		<?endif;?>
		</div>
		<h3><a href="/<?=$sub['urls']?>/"><?=$sub['module_menu_title'];?></a></h3>

	</div>
<? endforeach ; ?>
</div>
<p class="clear"></p>