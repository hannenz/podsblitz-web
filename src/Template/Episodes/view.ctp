<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Episode $episode
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Episode'), ['action' => 'edit', $episode->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Episode'), ['action' => 'delete', $episode->id], ['confirm' => __('Are you sure you want to delete # {0}?', $episode->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Episodes'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Episode'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Feeds'), ['controller' => 'Feeds', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Feed'), ['controller' => 'Feeds', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="episodes view large-9 medium-8 columns content">
    <h3><?= h($episode->title) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Title') ?></th>
            <td><?= h($episode->title) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Link') ?></th>
            <td><?= h($episode->link) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Guid') ?></th>
            <td><?= h($episode->guid) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Author') ?></th>
            <td><?= h($episode->author) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Feed') ?></th>
            <td><?= $episode->has('feed') ? $this->Html->link($episode->feed->title, ['controller' => 'Feeds', 'action' => 'view', $episode->feed->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($episode->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Duration') ?></th>
            <td><?= $this->Number->format($episode->duration) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Played') ?></th>
            <td><?= $this->Number->format($episode->played) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($episode->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($episode->modified) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Is Favorite') ?></th>
            <td><?= $episode->is_favorite ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Description') ?></h4>
        <?= $this->Text->autoParagraph(h($episode->description)); ?>
    </div>
</div>
