
<? if ($image['module_image_title']) : ?>
  <? if ($image['module_image_link']) : ?>
  <a href="<?= $image['module_image_link'] ?>"><b><?= $image['module_image_title'] ?></b></a>
  <? else : ?>
  <b><?= $image['module_image_title'] ?></b>
  <? endif; ?>
<? endif ; ?>

<p style="text-align:<?= $image['module_image_target'] ?>;">
<img src="/files/images<?= $image['module_image_src_data']['dirname'] . '300x300_'.$image['module_image_src_data']['name'] ?>" alt="" />
<? if ($image['module_image_is_popup']) : ?>
<br><i>ща всплывет :)</i>
<? endif ; ?>
</p>