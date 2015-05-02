
<?
/*
$t = array();
foreach ($menu as $m) : ?>
<? if ($m['level']<2): ?>
    <? if ( isset($t['level']) && $m['level']>$t['level']) : ?>
        <ul><li>
    <? elseif ( $m['level']<$t['level'] ) : ?>
        <?= str_repeat("</li></ul></div>", $t['level']-$m['level']) ?>
    <? elseif (isset($t['level'])) : ?>
        </div>
    <? endif; ?>

    <div class="menu-item"><a href="<?= ($m['module_menu_url_type']!=2?'/':'') . $m['urls'] . ($m['module_menu_url_type']!=2 && $m['urls']!=""?'/':'') ?>" class="<?= $m['class'] ?>"><?= $m['module_menu_title'] ?></a>

<?
$t = $m;
?>
<? endif; ?>
<? endforeach; ?>
</div>

<?*/
?>

<ul>
<?
$t = array();
foreach ($menu as $m) : ?>
<? if ($m['level']<2): ?>
    <? if ( isset($t['level']) && $m['level']>$t['level'] ) : ?>
        <ul>
    <? elseif ( $m['level']<$t['level'] ) : ?>
        <?= str_repeat("</li></ul>", $t['level']-$m['level']) ?>
    <? elseif (isset($t['level'])) : ?>
        </li>
    <? endif ; ?>

    <li><a href="<?= ($m['module_menu_url_type']!=2?'/':'') . $m['urls'] . ($m['module_menu_url_type']!=2 && $m['urls']!=""?'/':'') ?>" <?=($m['current']?'class="current"':'')?> ><?= $m['module_menu_title'] ?></a>
<?
	$t = $m;
	endif;
	endforeach;
?>
</li>
</ul>