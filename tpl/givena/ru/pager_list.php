<p class="clear"></p>
<? if ($pages>1) : ?>

<div class="postr">
<p style="text-align:center; width:100%;">
    <span class="arrow-left">
    <? if ($page==1) : ?>

    <img src="/tpl/givena/images/arrow-left.png">

    <? else : ?>

		<a href="<?= $APP->getCurUrl() . $lit ?>p=1"><img src="/tpl/givena/images/arrow-left.png"></a>

    <? endif ; ?>
    </span>

<? for ($j=1; $j<=$pages; $j++) : ?>
    <? if ($j==$page) : ?>
        <span class="act"><b><?= $j ?></b></span><? else : ?><span><a href="<?= $APP->getCurUrl() . $lit ?>p=<?= $j ?>"><?= $j ?></a></span><? endif ; ?>
<? endfor ; ?>

    <span class="arrow-right">
    <? if ($page==$pages) : ?>

    <img src="/tpl/givena/images/arrow-right.png">

    <? else : ?>

		<a href="<?= $APP->getCurUrl() . $lit ?>p=<?=$pages ?>"><img src="/tpl/givena/images/arrow-right.png"></a>

    <? endif ; ?>
    </span>

</p>
</div>
<? endif ; ?>
<p class="clear"></p>