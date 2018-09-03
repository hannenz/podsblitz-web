<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Feed $feed
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $feed->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $feed->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Feeds'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="feeds form large-9 medium-8 columns content">
    <?= $this->Form->create($feed) ?>
    <fieldset>
        <legend><?= __('Edit Feed') ?></legend>
        <?php
            echo $this->Form->control('url');
            echo $this->Form->control('title');
            echo $this->Form->control('poster');
            echo $this->Form->control('user_id', ['options' => $users]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>