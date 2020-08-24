<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\BuyerStatus[]|\Cake\Collection\CollectionInterface $buyerStatus
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Buyer Status'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="buyerStatus index large-9 medium-8 columns content">
    <h3><?= __('Buyer Status') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('buyer_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('phone_number') ?></th>
                <th scope="col"><?= $this->Paginator->sort('address') ?></th>
                <th scope="col"><?= $this->Paginator->sort('is_received') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($buyerStatus as $buyerStatus): ?>
            <tr>
                <td><?= $this->Number->format($buyerStatus->id) ?></td>
                <td><?= $this->Number->format($buyerStatus->buyer_id) ?></td>
                <td><?= h($buyerStatus->name) ?></td>
                <td><?= h($buyerStatus->phone_number) ?></td>
                <td><?= h($buyerStatus->address) ?></td>
                <td><?= h($buyerStatus->is_received) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $buyerStatus->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $buyerStatus->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $buyerStatus->id], ['confirm' => __('Are you sure you want to delete # {0}?', $buyerStatus->id)]) ?>
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
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
