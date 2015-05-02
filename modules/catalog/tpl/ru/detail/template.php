<?#Стандартный шаблон просмотра элемента#?>

<b>Страница элемента #<?= $catalog['module_list_item_id'] ?></b>
<br>
<br>
Дата обновления элемента: <?= date('d.m.Y H:i',$catalog['module_list_item_date_update']) ?>
<br>
<br>
Введите <u>&lt;?printarray($catalog);?&gt;</u> в шаблоне для получения списка всех переменных элемента.
<br>
<br>
<a href="<?= $APP->getCurUrl() ?>">Вернуться в раздел</a>