<div class="feeds-import-view">
	<?= $this->Form->create('Feeds', ['type' => 'file']) ?>
	<?= $this->Form->control('opmlfile', ['type' => 'file']); ?>
	<?= $this->Form->submit(__('Import'), ['class' => 'button']); ?>
	<?= $this->Form->end() ?>
</div>
