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
    <?= $this->Form->button(__('評価する')) ?>
    <?= $this->Form->end() ?>
</div>