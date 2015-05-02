
<? if (sizeof($modules)>0) : ?>

    <? foreach ($modules as $m) : ?>
        <? if ($m['is_pub']) : ?>
        <div class="admin-div-block-add-block"><a href="?admin_action=save&ajax=1&cl=_main&type=<?= $m['name'] ?>"><?= $m['title'] ?></a></div>
        <? endif ; ?>
    <? endforeach ; ?>

<? endif ; ?>