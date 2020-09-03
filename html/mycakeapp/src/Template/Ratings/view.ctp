<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Rating $rating
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Rating'), ['action' => 'edit', $rating->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Rating'), ['action' => 'delete', $rating->id], ['confirm' => __('Are you sure you want to delete # {0}?', $rating->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Ratings'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Rating'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Biditems'), ['controller' => 'Biditems', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Biditem'), ['controller' => 'Biditems', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="ratings view large-9 medium-8 columns content">
    <h3><?= h($rating->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Biditem') ?></th>
            <td><?= $rating->has('biditem') ? $this->Html->link($rating->biditem->name, ['controller' => 'Biditems', 'action' => 'view', $rating->biditem->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Comment') ?></th>
            <td><?= h($rating->comment) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($rating->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Target') ?></th>
            <td><?= $this->Number->format($rating->target) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Rater') ?></th>
            <td><?= $this->Number->format($rating->rater) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Score') ?></th>
            <td><?= $this->Number->format($rating->score) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($rating->created) ?></td>
        </tr>
    </table>
</div>