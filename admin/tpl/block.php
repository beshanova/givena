
<div class="admin-div-block-border">
	<div class="admin-div-icon-action-block">
		<div class="admin-div-icon-action" tm="<?= $tm_id ?>" cl="<?= $cl ?>">
			<img src="/admin/img/edit.png" alt="" style="float: left;">
					<span style="float: left;line-height: 24px;margin: 0px 0px 0px 5px;"><?if (($cl=='Slider')||($cl=='News')||($cl=='Gallery')||($cl=='Catalog')||($cl=='Spec'))  echo 'Добавить'; else echo'Редактировать';?></span>
		</div>
		<? if ( $is_pub ) : ?>
		<div class="top-clear-abs"></div>
		<div class="admin-div-icon-action-pub dropdown account">
			<img src="/admin/img/drop.png" alt="" style="float: left;margin: 0px 0px 0px 10px;">
			<div class="submenu" style="display: none; ">
				<ul class="root">
					<li>
						<div class="admin-div-icon-action-up" onclick="self.location='/_ajax/?cl=_Main&tm=<?= $tm_id ?>&admin_action=up&back_url=<?= urlencode($_SERVER['REQUEST_URI']) ?>'">
							<p>Переместить выше</p>
						</div>
					</li>
					<li>
						<div class="admin-div-icon-action-down" onclick="self.location='/_ajax/?cl=_Main&tm=<?= $tm_id ?>&admin_action=down&back_url=<?= urlencode($_SERVER['REQUEST_URI']) ?>'">
							<p>Переместить ниже</p>
						</div>
					</li>
					<li>
						<div class="admin-div-icon-action-delete" onclick="if (confirm('Точно удалить блок?')) { self.location='/_ajax/?cl=<?= $cl ?>&tm=<?= $tm_id ?>&admin_action=delete&back_url=<?= urlencode($_SERVER['REQUEST_URI']) ?>' }">
							<p>Удалить</p>
							</div>
					</li>
				</ul>
			</div>
		</div>
		<? endif ; ?>
	</div>
	<?= $block ?>
	<p class="clear"></p>
</div>


