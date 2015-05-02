<p class="clear"></p>
<? if ($pages>1) : ?>

<div class="postr">

    <? if ($page==1) : ?>
	  
		<span class="arrow-left"></span>
	 
    <? else : ?>
	  
		<a href="<?= $APP->getCurUrl() . $lit ?>p=1" class="arrow-left"></a>
	 
    <? endif ; ?>

<? for ($j=1; $j<=$pages; $j++) : ?>
    <? if ($j==$page) : ?>
	  
        <span class="act"><b><?= $j ?></b></span>
	  
    <? else : ?>
	  
        <span><a href="<?= $APP->getCurUrl() . $lit ?>p=<?= $j ?>"><?= $j ?></a></span>
	  
    <? endif ; ?>
    &nbsp;
<? endfor ; ?>

    <? if ($page==$pages) : ?>
	  
		<span class="arrow-right"></span>
	 
    <? else : ?>
	 
		<a href="<?= $APP->getCurUrl() . $lit ?>p=<?=$pages ?>" class="arrow-right"></a>
	  
    <? endif ; ?>

<div class="clear"></div>
</div>
<? endif ; ?>