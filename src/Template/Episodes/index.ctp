<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Episode[]|\Cake\Collection\CollectionInterface $episodes
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Episode'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Feeds'), ['controller' => 'Feeds', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Feed'), ['controller' => 'Feeds', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="episodes index large-9 medium-8 columns content">
    <h3><?= __('Episodes') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('title') ?></th>
                <th scope="col"><?= $this->Paginator->sort('link') ?></th>
                <th scope="col"><?= $this->Paginator->sort('guid') ?></th>
                <th scope="col"><?= $this->Paginator->sort('author') ?></th>
                <th scope="col"><?= $this->Paginator->sort('duration') ?></th>
                <th scope="col"><?= $this->Paginator->sort('feed_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('played') ?></th>
                <th scope="col"><?= $this->Paginator->sort('is_favorite') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($episodes as $episode): ?>
            <tr>
                <td><?= $this->Number->format($episode->id) ?></td>
                <td><?= h($episode->title) ?></td>
                <td><?= h($episode->link) ?></td>
                <td><?= h($episode->guid) ?></td>
                <td><?= h($episode->author) ?></td>
                <td><?= $this->Number->format($episode->duration) ?></td>
                <td><?= $episode->has('feed') ? $this->Html->link($episode->feed->title, ['controller' => 'Feeds', 'action' => 'view', $episode->feed->id]) : '' ?></td>
                <td><?= $this->Number->format($episode->played) ?></td>
                <td><?= h($episode->is_favorite) ?></td>
                <td><?= h($episode->created) ?></td>
                <td><?= h($episode->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $episode->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $episode->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $episode->id], ['confirm' => __('Are you sure you want to delete # {0}?', $episode->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
