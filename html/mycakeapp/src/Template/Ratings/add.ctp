<?php

/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Rating $rating
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Ratings'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Biditems'), ['controller' => 'Biditems', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Biditem'), ['controller' => 'Biditems', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="ratings form large-9 medium-8 columns content">
    <?= $this->Form->create($rating) ?>
    <fieldset>
        <legend><?= __('評価してください') ?></legend>
        <?php
        echo $this->Form->hidden('biditem_id', ['value' => $_GET['biditem_id']]);
        echo $this->Form->hidden('target', ['value' => $_GET['target']]);
        echo $this->Form->hidden('rater', ['value' => $_GET['rater']]);
        echo $this->Form->control('score', ['label' => '評価数(1~5の数字を入力下さい)']);
        echo $this->Form->control('comment', ['label' => 'コメント']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
