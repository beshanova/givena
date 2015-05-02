<?#Стандартный шаблон списка элементов#?>

Дата обновления элемента: <?= date('d.m.Y H:i',$catalog['module_list_item_date_update']) ?><br>
<a href="<?= $APP->getCurUrl ?>?id=<?= $catalog['module_list_item_id'] ?>">ID #<?= $catalog['module_list_item_id'] ?> элемента</a><br><br>

Введите <u>&lt;?printarray($catalog);?&gt;</u> в шаблоне для получения списка всех переменных элемента.