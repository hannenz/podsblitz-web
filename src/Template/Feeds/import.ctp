<div class="feeds-import-view">
	<?= $this->Form->create('Feeds', ['type' => 'file']) ?>
	<?php if (!isset($feeds)): ?>
		<label for="opmlfile" class="button"><?= __('Upload OPML File') ?></label>
		<input type="file" id="opmlfile" name="opmlfile" class="show-for-sr" />
	<?php else: ?>
		<?php foreach ($feeds as $n => $feed): ?>
			<input type="checkbox" id="cb-<?= $n ?>" name="feedurls[]" value="<?= $feed->url ?>" checked />
			<label for="cb-<?= $n ?>"><?= $feed->title ?></label>
		<?php endforeach ?>
		<?= $this->Form->control('step', ['type' => 'hidden', 'value' => 2]); ?>
	<?php endif ?>
	<?= $this->Form->submit(__('Import'), ['class' => 'button']); ?>
	<?= $this->Form->end() ?>
</div>
