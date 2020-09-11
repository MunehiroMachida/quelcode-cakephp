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
			<th scope="col" class="received"><?= __('Personal_Information') ?></th>
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
					error_reporting(0);
					$count = count($buyer_status);
					$information = false;
					for ($i = 0; $i <= $count; $i++) {
						if ($buyer_status[$i]['biditem_id'] === $info->biditem->id && $authuser['id'] === $info->user_id) {
							// informationは落札情報が入力されているか。
							$information = true;
							$is_received = $buyer_status[$i]['is_received'];
							$buyer_status_id = $buyer_status[$i]['id'];
							$buyer_status_biditem_id = $buyer_status[$i]['biditem_id'];
							$buyer_id = $buyer_status[$i]['buyer_id'];
							$address = $buyer_status[$i]['address'];
							$building_name = $buyer_status[$i]['name'];
							$phone_number = $buyer_status[$i]['phone_number'];
							break;
						}
					}
					$is_ratings = false;
					for ($j = 0; $j <= count($ratings); $j++) {
						if (($ratings[$j]['biditem_id'] === $buyer_status_biditem_id) && ($ratings[$j]['rater'] === $buyer_id)) {
							$is_ratings = true;
							break;
						}
					}
					$is_sent = $info->biditem->is_sent;
					?>
					<?= $this->Html->link(__('メッセージ'), ['action' => 'msg', $info->id]) ?>
				</td>
				<td class="received">
					<?php if ($information === true && $is_received === false && $is_sent === true) : ?>
						<?php
						echo $this->Form->create(null, ['type' => 'post', 'url' => ['controller' => 'Auction', 'auction' => 'home']]);
						echo $this->Form->hidden('buyer', ['value' => $buyer_status[$i]['id']]);
						echo $this->Form->button('受け取り完了');
						echo $this->Form->end();
						?>
					<?php elseif ($information === true && $is_received === false && $is_sent === false) : ?>
						<?= h('商品が発送されるまでお待ちください') ?>
					<?php elseif ($buyer_status_biditem_id !== $info->biditem->id) : ?>
						<?= $this->Html->link(__('お届け先を入力してください'), ['action' => 'buyerinfo', 'biditem_id' => $info->biditem->id]) ?>
					<?php elseif ($is_ratings === true) : ?>
						<?= h('評価しました') ?>
					<?php elseif ($is_ratings === false) : ?>
						<?= $this->Html->link(__('評価する'), ['action' => '../Ratings/add', 'biditem_id' => $buyer_status_biditem_id]) ?>
					<?php else : ?>
						<?= h('') ?>
					<?php endif; ?>
				</td>
				<td>
					<?php if ($information === true) : ?>
						<p><?= h('送信済みです。') ?></p>
						<p><?= h('建物名：' . $building_name) ?></p>
						<p><?= h('住所：' . $address) ?></p>
						<p><?= h('電話番号：' . $phone_number) ?></p>
					<?php else : ?>
						<span class="red">
							<?= h('住所が送信されていません') ?>
						</span>
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
<h6><?= $this->Html->link(__('出品情報に移動 >>'), ['action' => 'home2']) ?></h6>