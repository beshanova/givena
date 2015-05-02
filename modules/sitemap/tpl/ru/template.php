
<ul>
<?
$t = array();
foreach ($sitemap as $m) : ?>

    <? if ( isset($t['level']) && $m['level']>$t['level'] ) : ?>
        <ul>
    <? elseif ( $m['level']<$t['level'] ) : ?>
        <?= str_repeat("</li></ul>", $t['level']-$m['level']) ?>
    <? elseif (isset($t['level'])) : ?>
        </li>
    <? endif ; ?>

    <li><a href="<?= ($m['module_menu_url_type']!=2?'/':'') . $m['urls'] . ($m['module_menu_url_type']!=2 && $m['urls']!=""?'/':'') ?>" ><?= $m['module_menu_title'] ?></a>

<?
$t = $m;
endforeach; ?>
</li>
</ul>