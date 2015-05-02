<div class="form-contacts" style="border-top:0;">

<? if ($mes_ok) : ?>
    <p><?= implode(BR, $mes_ok) ?></p>
<? else : ?>
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
	<td style="text-align: center;"><input type="submit" class="send" value=""></td>
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