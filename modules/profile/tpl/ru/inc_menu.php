
<link type="text/css" rel="stylesheet" href="/tpl/<?= $_SESSION['_SITE_']['theme'] ?>/css/profile.css">

<h1>Профиль</h1>

[<a href="/profile/?cl=<?= $this->getClassName() ?>&tm=<?= $this->tm_id ?>&a=index"><span>Профиль</span></a>]
&nbsp;
[<a href="/profile/?cl=<?= $this->getClassName() ?>&tm=<?= $this->tm_id ?>&a=orders"><span>Просмотр заказов</span></a>]
&nbsp;
[<a href="/profile/?cl=<?= $this->getClassName() ?>&tm=<?= $this->tm_id ?>&a=edit" class = "button"><span>Редактирование профиля</span></a>]
<hr><br>