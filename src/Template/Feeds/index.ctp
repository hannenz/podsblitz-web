<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Feed[]|\Cake\Collection\CollectionInterface $feeds
 */
?>
<section class="feeds-view index">
    <h3><?= sprintf('%u %s', $feeds->count(), __('Feeds')) ?></h3>

	<ul class="feeds grid-x">
		<?php foreach ($feeds as $feed): ?>

			<li class="cell small-4 medium-3 large-2">
				<a class="feed card cell" href="/feeds/view/<?= $feed->id ?>">
					<?= $this->Html->image('/'.$feed->poster, ['class' => 'feed__poster']); ?>
					<div class="card-section">
						<h4><?= $feed->title ?></h4>
					</div>
				</a>
			</li>
		<?php endforeach ?>
	</ul>
</section>


