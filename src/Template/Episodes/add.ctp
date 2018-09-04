<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Episode $episode
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Episodes'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Feeds'), ['controller' => 'Feeds', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Feed'), ['controller' => 'Feeds', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="episodes form large-9 medium-8 columns content">
    <?= $this->Form->create($episode) ?>
    <fieldset>
        <legend><?= __('Add Episode') ?></legend>
        <?php
            echo $this->Form->control('title');
            echo $this->Form->control('description');
            echo $this->Form->control('link');
            echo $this->Form->control('guid');
            echo $this->Form->control('author');
            echo $this->Form->control('duration');
            echo $this->Form->control('feed_id', ['options' => $feeds]);
            echo $this->Form->control('played');
            echo $this->Form->control('is_favorite');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
