<h1><?=$title?></h1>

<p>Вы искали: <?= $data['query']?></p>

<? foreach ($data['results'] as $d) : ?>



<? endforeach ; ?>