<h2><?= $authuser['username'] ?> のホーム</h2>
<h3>※落札情報</h3>
<table cellpadding="0" cellspacing="0">
	<thead>
		<tr>
			<th scope="col"><?= $this->Paginator->sort('id') ?></th>
			<th class="main" scope="col"><?= $this->Paginator->sort('name') ?></th>
			<th scope="col"><?= $this->Paginator->sort('created') ?></th>
			<th scope="col" class="actions"><?= __('Actions') ?></th>
			<th scope="col" class="received"><?= __('Received') ?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($bidinfo as $info) : ?>
			<tr>
				<td><?= h($info->id) ?></td>
				<td><?= h($info->biditem->name) ?></td>
				<td><?= h($info->created) ?></td>
				<td class="actions">
					<?php
					$count = count($buyer_status);
					for ($i = 0; $i <= $count; $i++) {
						if ($buyer_status[$i]['biditem_id'] === $info->biditem->id) {
							// informationは落札情報が入力されているか。
							$information = true;
							$is_received = $buyer_status[$i]['is_received'];
							break;
						}
					}
					?>
					<?php if ($information === true) : ?>
						<?= $this->Html->link(__('メッセージ'), ['action' => 'msg', $info->id]) ?>
					<?php else : ?>
						<?= $this->Html->link(__('お届け先'), ['action' => 'buyerinfo', 'biditem_id' => $info->biditem->id]) ?>
					<?php endif; ?>
				</td>
				<td class="received">
					<?php if ($is_received === false) : ?>
						<?php
						echo $this->Form->create(null, ['type' => 'post', 'url' => ['controller' => 'Auction', 'auction' => 'home']]);
						echo $this->Form->hidden('buyer', ['value' => $buyer_status[$i]['id']]);
						echo $this->Form->button('受け取り完了');
						echo $this->Form->end();
						?>
					<?php else : ?>
						<?= h('受け取り完了しました') ?>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
		<!-- <?php var_dump($is_received); ?> -->

	</tbody>
</table>

<div class="paginator">
	<ul class="pagination">
		<?= $this->Paginator->first('<< ' . __('first')) ?>
		<?= $this->Paginator->prev('< ' . __('previous')) ?>
		<?= $this->Paginator->numbers() ?>
		<?= $this->Paginator->next(__('next') . ' >') ?>
		<?= $this->Paginator->last(__('last') . ' >>') ?>
	</ul>
</div>
<h6><?= $this->Html->link(__('出品情報に移動 >>'), ['action' => 'home2']) ?></h6>