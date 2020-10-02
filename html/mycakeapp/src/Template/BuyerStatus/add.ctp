<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\BuyerStatus $buyerStatus
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Buyer Status'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="buyerStatus form large-9 medium-8 columns content">
    <?= $this->Form->create($buyerStatus) ?>
    <fieldset>
        <legend><?= __('Add Buyer Status') ?></legend>
        <?php
            echo $this->Form->control('buyer_id');
            echo $this->Form->control('name');
            echo $this->Form->control('phone_number');
            echo $this->Form->control('address');
            echo $this->Form->control('is_received');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
