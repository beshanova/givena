
<? if (sizeof($topics)>0) : ?>
    <? if ($topics[0]['module_menu_url']!='') : ?>
    <a href="/">Главная</a>
    <? endif ; ?>

    <? $path='/'; foreach (array_reverse($topics) as $t) : ?>
        <? if (isset($t['module_menu_url_full']) && $t['module_menu_url_full']!='') : ?>

        &nbsp;->&nbsp;<a href="<?= $t['module_menu_url_full'] ?>"><?= $t['f_title']?></a>

        <? else : ?>

        <? $path .= '' . $t['module_menu_url'] . ($t['module_menu_url_type']!=2 && $t['module_menu_url']!=""?'/':'') ?>
        &nbsp;->&nbsp;<a href="<?= $path ?>"><?= $t['module_menu_title']?></a>

        <? endif ; ?>

    <? endforeach ; ?>
<? endif ; ?>