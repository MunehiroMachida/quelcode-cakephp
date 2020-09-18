<h2>発送先情報の入力をお願いします</h2>
<?= $this->Form->create($buyerstatus) ?>
<fieldset>
	<?php
	echo $this->Form->control('name');
	echo $this->Form->control('phone_number');
	echo $this->Form->control('address');
	?>
</fieldset>
<?= $this->Form->button(__('Submit')) ?>
<?= $this->Form->end() ?>
