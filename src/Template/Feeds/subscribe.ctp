<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Feed $feed
 */
?>

<div class="">
    <?= $this->Form->create($feed) ?>
    <fieldset>
        <legend><?= __('Subscribe to a new Feed') ?></legend>
        <?php
            echo $this->Form->control('url');
            // echo $this->Form->control('title');
            // echo $this->Form->control('poster');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
