<div class="form-contacts">
<img alt="" class="twirl" src="/tpl/givena/images/twirl.gif">

<script src="/tpl/givena/js/mailform.js" type="text/javascript"></script>

<h2>Обратная связь</h2>

<? if ($mes_ok) : ?>
    <p><?= implode(BR, $mes_ok) ?></p>
<? else : ?>

<? if ($mes_err) : ?>
    <p><?= implode(BR, $mes_err) ?></p>
<? endif ; ?>

<form action="" method="post" enctype="multipart/form-data">
<input type="hidden" name="site_action" value="send" />
<table>
<? foreach ($fields as $f) : ?>
<tr>
    <td style="width:75px;" class="label">
		<?= $f['title'] ?>:
	</td>
	<td style="">
		<? if (is_array($f['field'])) : ?>
        <?= implode('<br />', $f['field']) ?>
		<? else : ?>
			<?= $f['field'] ?>
		<? endif ; ?>
	</td>
</tr>
<? endforeach ; ?>
<tr>
	<td style="width:75px;"></td>
	<td style="text-align: center;"><input type="submit" class="send" value="" onclick="return testDataMailform();"></td>
</tr>
</table>
<br />
<style>
.cont-text p a img {
    border: 1px solid #80B61B!important;
    padding: 5px;
}
</style>



</form>
<? endif ; ?>
</div>