
<link type="text/css" rel="stylesheet" href="/tpl/<?= $_SESSION['_SITE_']['theme'] ?>/css/profile.css">

<h1>Профиль</h1>

<div class="auth-menu">
  <a href="/profile/?cl=<?= $this->getClassName() ?>&tm=<?= $this->tm_id ?>&a=index"<?=($act=='index'?' class="act"':'')?>>Список заказов</a>
  <a href="/profile/?cl=<?= $this->getClassName() ?>&tm=<?= $this->tm_id ?>&a=edit"<?=($act=='edit'?' class="act"':'')?>>Данные профиля</a>
</div>