<?

function write_menu($arr, $urls, $level){
?>

	<? 
	if (!empty($arr))
	foreach ($arr as $m) : ?>
		<div class="menu-left-<?=$m['level'];?>lvl <?= ($m['current']?'act':'');?> ">
			<a href="<?= ($m['module_menu_url_type']!=2?'/':'') . $urls.$m['urls'] . ($m['module_menu_url_type']!=2 && $m['urls']!=""?'/':'') ?>" class="<?= $m['class'] ?> <?= ($m['current']?'act':'');?> lvl<?=$m['level'];?>"><?= $m['module_menu_title'] ?></a>
			<?
		
			$urls.=$m['urls'].'/';
			$urls = write_menu($m['sub'], $urls, $level);
			$urls = str_replace($m['urls'].'/', '', $urls);
			
				
			?>
		</div>
	  
	<? endforeach; ?>
<?
return $urls;
}
?>

<?
	write_menu($menu,'catalog/', 1);
?>

<script>
$(document).ready(function()
{
  $('div.act').parents('.menu-l div').addClass('act');
  $('.menu-l div.act > a').addClass('act');
});
</script>