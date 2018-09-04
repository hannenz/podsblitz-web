<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Feed $feed
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Feed'), ['action' => 'edit', $feed->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Feed'), ['action' => 'delete', $feed->id], ['confirm' => __('Are you sure you want to delete # {0}?', $feed->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Feeds'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Feed'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="feeds view large-9 medium-8 columns content">
    <h3><?= h($feed->title) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Url') ?></th>
            <td><?= h($feed->url) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Title') ?></th>
            <td><?= h($feed->title) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Poster') ?></th>
            <td><?= h($feed->poster) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('User') ?></th>
            <td><?= $feed->has('user') ? $this->Html->link($feed->user->id, ['controller' => 'Users', 'action' => 'view', $feed->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($feed->id) ?></td>
        </tr>
    </table>

	<h4>Episodes</h4>
	<table>
			<?php foreach ($feed->items as $episode): ?>
			<tr>
				<td> <?= $episode->title; ?> </td>
				<td> <?= $episode->description; ?> </td>
				<td> <?= $episode->pubDate; ?> </td>
			</tr>
			<?php endforeach ?>
	</table>
</div>
