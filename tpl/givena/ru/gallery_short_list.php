<? foreach ($catalog as $n) : ?>
	<div class="foto_all">
	<div class="foto_up"></div>
	<div class="foto">
		<img src="/files/resize_cache<?= $n['f_file_data']['dirname'] . '110x120-crop_'.$n['f_file_data']['name'] ?>" alt="" />
	</div>
	</div>
<? endforeach ; ?>