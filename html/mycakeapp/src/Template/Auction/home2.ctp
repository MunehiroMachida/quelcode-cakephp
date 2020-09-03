<h2><?= $authuser['username'] ?> のホーム</h2>
<h3>※出品情報</h3>
<table cellpadding="0" cellspacing="0">
	<thead>
		<tr>
			<th scope="col"><?= $this->Paginator->sort('id') ?></th>
			<th class="main" scope="col"><?= $this->Paginator->sort('name') ?></th>
			<th scope="col"><?= $this->Paginator->sort('created') ?></th>
			<th scope="col" class="actions"><?= __('Actions') ?></th>
			<th scope="col" class="sent"><?= __('Sent') ?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($biditems as $biditem) : ?>
			<tr>
				<td><?= h($biditem->id) ?></td>
				<td><?= h($biditem->name) ?></td>
				<td><?= h($biditem->created) ?></td>
				<td class="actions">
					<?php if ($biditem->bidinfo['biditem_id'] === $biditem->id) : ?>
						<?= $this->Html->link(__('落札者とのメッセージ'), ['action' => 'msg', $biditem->bidinfo->id]) ?>
					<?php else : ?>
						<?= h('落札者がいません') ?>
					<?php endif; ?>
				</td>
				<td class="sent">
					<?php
					error_reporting(0);
					$count = count($buyer_status);
					for ($i = 0; $i <= $count; $i++) {
						if ($buyer_status[$i]['biditem_id'] === $biditem['id']) {
							$information = true;
							$is_received = $buyer_status[$i]['is_received'];
							$buyer_status_id = $buyer_status[$i]['id'];
							$buyer_status_biditem_id = $buyer_status[$i]['biditem_id'];
							$buyer_id = $buyer_status[$i]['buyer_id'];
							break;
						} else {
							$information = false;
						}
					}
					for ($j = 0; $j <= count($ratings); $j++) {
						if (($ratings[$j]['biditem_id'] === $buyer_status_biditem_id) && ($ratings[$j]['rater'] === $biditem['user_id'])) {
							$is_ratings = true;
							break;
						} else {
							$is_ratings = false;
						}
					}
					?>
					<?php if ($biditem['is_sent'] === false && $is_received === false && $information === true) : ?>
						<?php
						echo $this->Form->create(null, ['type' => 'post', 'url' => ['controller' => 'Auction', 'auction' => 'home2']]);
						echo $this->Form->hidden('item', ['value' => $biditem['id']]);
						echo $this->Form->button('発送完了');
						echo $this->Form->end();
						?>
					<?php elseif ($biditem['is_sent'] === true && $is_received === false && $information === true) : ?>
						<?= h('発送完了しました') ?>
					<?php elseif (($biditem['is_sent'] === false && $is_received === false && $information === false) || is_null($buyer_status[$i])) : ?>
						<?= h('落札者が情報を入力するまでお待ちください') ?>
					<?php elseif ($is_ratings === true) : ?>
						<?= h('評価しました') ?>
					<?php elseif ($is_ratings === false) : ?>
						<?= $this->Html->link(__('評価する'), ['action' => '../Ratings/add', 'biditem_id' => $biditem['id'], 'target' => $buyer_id, 'rater' => $biditem['user_id']]) ?>
					<?php else : ?>
						<?= h('') ?>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
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
<h6><?= $this->Html->link(__('<< 落札情報に戻る'), ['action' => 'home']) ?></h6>
