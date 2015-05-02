
<?
$t = array();
foreach ($menu as $m) : ?>

    <? if ( isset($t['level']) && $m['level']>$t['level'] ) : ?>
        <ul>
    <? elseif ( $m['level']<$t['level'] ) : ?>
        <?= str_repeat("</li></ul>", $t['level']-$m['level']) ?>
    <? elseif (isset($t['level'])) : ?>
        </span>
    <? endif ; ?>

    <span><a href="<?= ($m['module_menu_url_type']!=2?'/':'') . $m['urls'] . ($m['module_menu_url_type']!=2 && $m['urls']!=""?'/':'') ?>" class="<?= $m['class'] ?>"><?= $m['module_menu_title'] ?></a>
<?
$t = $m;
endforeach; ?>
<span><a href="javascript://" onclick="ShowBasketOrder('catalog'); window.scrollTo(0, 0); return 1;">Корзина</a></span>

</span>

