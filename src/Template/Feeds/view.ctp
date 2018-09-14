<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Feed $feed
 */
?>
<div class="xfeed">
    <h3><?= h($feed->title) ?></h3>
	<figure class="feed__poster">
		<?= $this->Html->image('/'.$feed->poster); ?>
	</figure>
	<div class="feed__description"><?= $feed->description ?></div>
	<small><code><?= $feed->url ?></code></small>

	<h4><?= __('Episodes') ?></h4>
	<p><?php echo $this->Html->link('<svg class="icon"><use xlink:href="/dist/img/ikonate.svg#refresh"></use></svg> Sync episodes', ['controller' => 'feeds', 'action' => 'syncEpisodes', $feed->id], ['class' => 'button', 'escape' => false]); ?></p>
	<p>
		<?php echo $this->Html->link('Fetch poster', ['controller' => 'feeds', 'action' => 'fetchPoster', $feed->id], ['class' => 'button']); ?>
		<?php echo $this->Html->link('Sync episodes', ['controller' => 'feeds', 'action' => 'syncEpisodes', $feed->id], ['class' => 'button']); ?>
	</p>
	<table>
		<?php if (!empty($feed->episodes)): ?>
			<?php foreach ($feed->episodes as $episode): ?>
			<tr>
				<td style="width:140px; vertical-align: top"> <?= $this->Html->image('/' . $episode->poster, ['style' => 'display: block; width: 140px']); ?> </td>
				<td>

					<div style="color:#a0a2a4"><?= $episode->published->format('d.m.Y') ?> &middot; <?= $episode->published->format('H:i'); ?> Uhr</div>
					<div>
						<a href="#"><svg class="icon"><use xlink:href="/dist/img/ikonate.svg#download"></use></svg> Download</a>
						<a href="#"><svg class="icon"><use xlink:href="/dist/img/ikonate.svg#favourite"></use></svg> Favorite</a>
					</div>

					<h4><?= $episode->title; ?></h4>
					</div>
						<?= h(strip_tags($episode->description)); ?>
					</div>
					<div>
						<audio src="<?= $episode->fileurl ?>" type="<?= $episode->filetype ?>" controls></audio>
					</div>

				 </td>
				<td> <?php printf("%02u:%02u", $episode->duration / 3600, $episode->duration / 60 % 60); ?>
			</tr>
			<?php endforeach ?>
		<?php endif ?>
	</table>
</div>
