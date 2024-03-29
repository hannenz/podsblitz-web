<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = 'CakePHP: the rapid development php framework';
?>
<!doctype html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('main.css') ?>
	<?= $this->Html->script('vendor/jquery.min.js') ?>
	<?= $this->Html->script('vendor/foundation.min.js') ?>
	<?= $this->Html->script('main.min.js') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>

<body>

	<div class="title-bar" data-responsive-toggle="responsive-menu" data-hide-for="medium">
	  <button class="menu-icon" type="button" data-toggle="responsive-menu"></button>
	  <div class="title-bar-title">Menu</div>
	</div>

	<div class="top-bar" id="responsive-menu">
		<div class="top-bar-left">
			<ul class="dropdown menu" data-dropdown-menu>
				<li class="menu-text brand"> <?= $this->Html->image('/dist/img/logo2.svg', ['url' => '/']); ?> </li>
				<li class="has-submenu">
					<?= $this->Html->link(__('Subscriptions'), ['controller' => 'feeds', 'action' => 'index']) ?>
					<ul class="submenu menu vertical" data-submenu>
					<li><?= $this->Html->link(_('Subscribe to a new Feed'), ['controller' => 'feeds', 'action' => 'subscribe']); ?></li>
						<li><?= $this->Html->link(__('Import'), ['controller' => 'feeds', 'action' => 'import']) ?></li>
						<li><a href="#0">Three</a></li>
					</ul>
				</li>
				<li><a href="#0">Player</a></li>
			</ul>
		</div>
		<div class="top-bar-right">
			<ul class="dropdown menu" data-dropdown-menu>
				<li><input type="search" placeholder="Search"></li>
				<li><button type="button" class="button">Search</button></li>
				<li class="has-submenu">
					<svg class="icon"><use xlink:href="/dist/img/ikonate.svg#user"></use></svg> <?= $this->Session->read('Auth.User.email'); ?>
					<ul class="submenu menu vertical" data-submenu>
						<li><?= $this->Html->link(_('Settings'), '#'); ?></li>
						<li><?= $this->Html->link(_('Logout'), ['controller' => 'users', 'action' => 'logout']); ?></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>

    <?= $this->Flash->render() ?>
    <div class="container">
        <?= $this->fetch('content') ?>
    </div>
    <footer class="main-footer">
		Podsblitz! &copy;2018 <?= $this->Html->link('hannenz', 'https://www.hannenz.de') ?>
    </footer>
</body>
</html>
