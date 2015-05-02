<div class="<?= (!isset($params['is_active']) || $params['is_active']==1 ? 'admin-div-block-border' : 'admin-div-block-border-no-active') ?>">
	<div class="admin-div-icon-action-block-item">
		<div class="admin-div-icon-action" tm="<?= $tm_id ?>" cl="<?= $cl ?>" item="<?= $item_id ?>">
			<img src="/admin/img/edit.png" alt="" style="float: left;">
			<span style="float: left;line-height: 24px;margin: 0px 0px 0px 5px;">Редактировать</span>
		</div>
		<div class="top-clear-abs"></div>
		<div class="admin-div-icon-action-pub dropdown account-item">
			<img src="/admin/img/drop.png" alt="" style="float: left;margin: 0px 0px 0px 10px;">
			<div class="submenu-item" style="display: none; ">
				<ul class="root-item">
					<li>
						<div onclick="self.location='/_ajax/?cl=<?= $cl ?>&tm=<?= $tm_id ?>&item_id=<?= $item_id ?>&admin_action=up&back_url=<?= urlencode($_SERVER['REQUEST_URI']) ?>'" class="admin-div-icon-action-up">
							<p>Переместить выше</p>
						</div>
					</li>
					<li>
						<div onclick="self.location='/_ajax/?cl=<?= $cl ?>&tm=<?= $tm_id ?>&item_id=<?= $item_id ?>&admin_action=down&back_url=<?= urlencode($_SERVER['REQUEST_URI']) ?>'" class="admin-div-icon-action-down">
							<p>Переместить ниже</p>
						</div>
					</li>
					<li>
						<div class="admin-div-icon-action-delete" onclick="if (confirm('Точно удалить?')) { self.location='/_ajax/?cl=<?= $cl ?>&d=item&tm=<?= $tm_id ?>&item_id=<?= $item_id ?>&admin_action=item_delete&back_url=<?= urlencode($_SERVER['REQUEST_URI']) ?>' }">
							<p>Удалить</p>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<?= $block ?>
	<p class="clear"></p>
</div>