<?#Шаблон расширенного поиска#?>
<form action="/advanced_search/" method="get">

	<input type="hidden" value="adv_search_res" name="action">
  	<input type="hidden" value="advanced_search" name="tm">

	<div class="search-filters">
		<div class="fl-left">
			<p class="name-filter">Цвет:</p>
			<div class="relative_div">
				<input type="hidden" value="<?=$_REQUEST['color']?>" name="color">
				<input class="input-text myselect" type="text" value="<?=($_REQUEST['color'])?htmlspecialchars($_REQUEST['color']):'не выбрано'?>" maxlength="100"/>
				<img src="/tpl/givena/images/inp-serch.png" alt="Выбрать" class="myselect" />
				<ul class="ulselect">
					<li name="Розовый">Розовый</li>
					<li name="Красный">Красный</li>
					<li name="Желтый">Желтый</li>
					<li name="Белый, Кремовый">Белый, Кремовый</li>
					<li name="Оранжевый">Оранжевый</li>
					<li name="Пурпурный, Лиловый">Пурпурный, Лиловый</li>
					<li name="Двухцветные">Двухцветные</li>
                    
                    <li name="Бледно-лавандовый">Бледно-лавандовый</li>
                    <li name="Лавандовый">Лавандовый</li>
                    <li name="Пурпурный">Пурпурный</li>
                    <li name="Коричневые оттенки">Коричневые оттенки</li>
                    <li name="Полосатые">Полосатые</li>
				</ul>
			</div>
		</div>
		<div class="fl-left">
			<p class="name-filter">По высоте:</p>
			<div class="relative_div">
				<input class="interval" type="text" value="<?=htmlspecialchars($_REQUEST['height_from'])?>" maxlength="100" name="height_from"/>
				<span>&mdash;</span>
				<input class="interval" type="text" value="<?=htmlspecialchars($_REQUEST['height_to'])?>" maxlength="100" name="height_to"/>
			</div>
		</div>
		<input class="find" type="submit" value="" />
		<p class="clear"></p>
	</div>
</form>