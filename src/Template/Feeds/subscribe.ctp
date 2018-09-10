<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Feed $feed
 */
?>

<div class="">
    <?= $this->Form->create($feed) ?>
	<div class="input-group">
		<label class="input-group-label" for="url">URL</label>
		<input class="input-group-field" id="url" name="url" type="text" size="48" autofocus />
		<div class="input-group-button">
			<input type="submit" class="button" value="<?= __('Subscribe') ?>" />
		</div>
	</div>

    <?= $this->Form->end() ?>
</div>
