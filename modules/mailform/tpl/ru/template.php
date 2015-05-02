<b>Форма связи</b>
<br><br>

<? if ($mes_ok) : ?>

    <p><?= implode(BR, $mes_ok) ?></p>

<? else : ?>

<form action="" method="post" enctype="multipart/form-data">
<input type="hidden" name="site_action" value="send" />

<? foreach ($fields as $f) : ?>
    <?= $f['title'] ?>:<br>
    <? if (is_array($f['field'])) : ?>
        <?= implode('<br />', $f['field']) ?>
    <? else : ?>
        <?= $f['field'] ?>
    <? endif ; ?>
    <br><br>
<? endforeach ; ?>
<input type="submit" value="Отправить">&nbsp;<input type="reset" value="Очистить" onclick="return confirm('Точно очистить?');">
</form>

<? endif ; ?>