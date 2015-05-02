<?if (!empty($data)):?>
<table border="0" class="basket_history_new" style="width: 950px;">
    <tr  style="font-weight: bold;">
        <td style="width:50px;">Номер</td>
        <td style="width:110px;">Дата</td>
        <td style="width:60px;">Товаров</td>
        <td style="width:80px;">Сумма к оплате</td>
        <td style="width:80px;">Оплачено</td>
        <td style="width:80px;">Статус</td>
    </tr>

    <? foreach($data as $z) : ?>
        <?$st_id = $z['module_basket_item_status']; ?>
        <tr style="cursor:pointer;">
            <td onclick="$(this).parent().next().toggle();"><?= $z['module_basket_item_id'] ?></td>
            <td onclick="$(this).parent().next().toggle();"><?= dateFormat($z['module_basket_item_date_update'], true) ?></td>
            <td onclick="$(this).parent().next().toggle();"><?= $z['cnt'] ?></td>
            <td onclick="$(this).parent().next().toggle();"><?= $z['summ'] ?></td>
            <td><input type="text" style="width:50%;" id="dop-summ-<?= $z['module_basket_item_id'] ?>" value="<?= ($z['module_basket_item_summ2']>0?$z['module_basket_item_summ2']:'') ?>" />&nbsp;<input type="button" value="S" onclick="saveDopSumm(<?= $z['module_basket_item_id'] ?>);"><div style="display:none; color:#0000ff;" id="ok-<?= $z['module_basket_item_id'] ?>"></div></td>
            <td><select onchange="newStatusElement(<?= $z['module_basket_item_id'] ?>, $(this).val());">
                    <? foreach($status as $s2_id=>$s2_t) : ?>
                        <option value="<?= $s2_id ?>"<?= ($st_id==$s2_id?' selected':'') ?>><?= $s2_t ?></option>
                    <? endforeach; ?>
                </select></td>
        </tr>
        <tr style="display:none;">
            <td colspan="2">
                <? foreach ($z['client'] as $c) : ?>
                    <p><?= $c['title'] ?>: <?= $c['value'] ?></p>
                <? endforeach ; ?>
            </td>
            <td colspan="4">
                <? foreach ($z['items'] as $i) : ?>
                    <p>
                        <? foreach ($i as $k=>$t) : ?>
                            <? if (preg_match('~^f_~is', $k) && !is_array($t) && $t!="") : ?>
                                <?= $t ?>,
                            <? endif ; ?>
                        <? endforeach ; ?>
                        <b>(<?= $i['price'] ?> руб., <?= $i['cnt'] ?> шт.)</b>
                    </p>
                <? endforeach ; ?>
            </td>
        </tr>

    <? endforeach ; ?>

</table>
<?else:?>
    <p>Ничего не найдено.</p>
<?endif;?>