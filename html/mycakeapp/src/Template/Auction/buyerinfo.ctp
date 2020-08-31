<h2>発送先情報の入力をお願いします</h2>
<?= $this->Form->create($buyerstatus) ?>
<fieldset>
	<?php
	echo $this->Form->hidden('buyer_id', ['value' => $authuser['id']]);
	echo $this->Form->control('name');
	echo $this->Form->control('phone_number');
	echo $this->Form->control('address');
	echo $this->Form->hidden('is_received', ['value' => 0]);
	echo $this->Form->hidden('biditem_id', ['value' => intval($_GET['biditem_id'])]);
	?>
</fieldset>
<?= $this->Form->button(__('Submit')) ?>
<?= $this->Form->end() ?>