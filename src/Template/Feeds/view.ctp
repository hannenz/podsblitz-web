<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Feed $feed
 */
?>
<div class="feed">
    <h3><?= h($feed->title) ?></h3>
	<figure class="feed__poster">
		<?= $this->Html->image('/'.$feed->poster); ?>
	</figure>
	<div class="feed__description"><?= $feed->description ?></div>
	<small><code><?= $feed->url ?></code></small>

	<h4><?= __('Episodes') ?></h4>
	<table>
		<?php if (!empty($feed->episodes)): ?>
			<?php foreach ($feed->episodes as $episode): ?>
			<tr>
				<td> <?= $episode->title; ?> </td>
				<td> <?= $episode->description; ?> </td>
				<td> <?= $episode->duration ?></td>
				<td> <?php printf("%02u:%02u", $episode->duration / 3600, $episode->duration / 60 % 60); ?>
				<td> Veröffentlicht am <?= $episode->published->format('D, d.m.Y H:i'); ?> </td>
			</tr>
			<?php endforeach ?>
		<?php endif ?>
	</table>
	<?php echo $this->Html->link('Sync episodes', ['controller' => 'feeds', 'action' => 'syncEpisodes', $feed->id], ['class' => 'button']); ?>
</div>
