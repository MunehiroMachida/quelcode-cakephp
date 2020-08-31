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
					<?php if (!empty($biditem->bidinfo)) : ?>
						<?= $this->Html->link(__('落札者とのメッセージ'), ['action' => 'msg', $biditem->bidinfo->id]) ?>
					<?php endif; ?>
				</td>
				<td class="sent">
					<?php if ($biditem['is_sent'] === false) : ?>
						<?php
						echo $this->Form->create(null, ['type' => 'post', 'url' => ['controller' => 'Auction', 'auction' => 'home2']]);
						echo $this->Form->hidden('item', ['value' => $biditem['id']]);
						echo $this->Form->button('発送完了');
						echo $this->Form->end();
						?>
					<?php else : ?>
						<?= h('NULL') ?>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<?php var_dump($biditem['id']) ?>
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