<? if ($pages>1) : ?>

<p class="clear"></p>
<br>
<?$url = preg_replace('~\/\/~','/',$APP->getCurUrl());?>

<p align="center">
    <? if ($page!=1) : ?>
		<a href="javascript://" onclick="<?= str_replace('#PAGE#', 1, $func) ?>" title="Первая">&lt;&lt;</a>
    <? endif ; ?>
	&nbsp;

  <? for ($j=1; $j<=$pages; $j++) : ?>
		<? if ($j==$page) : ?>
			<b><?= $j ?></b>
		<? else : ?>
			<a href="javascript://" onclick="<?= str_replace('#PAGE#', $j, $func) ?>"><?= $j ?></a>
		<? endif ; ?>
		&nbsp;
	<? endfor ; ?>

    <? if ($page!=$pages) : ?>
		<a href="javascript://" onclick="<?= str_replace('#PAGE#', $pages, $func) ?>" title="Последняя">&gt;&gt;</a>
    <? endif ; ?>
</p>

<? endif ; ?>