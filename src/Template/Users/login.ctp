<div class="login-box">
	<h1><?= __('Login'); ?></h1>

	<?= $this->Form->create(); ?>
	<?= $this->Form->control('email') ?>
	<?= $this->Form->control('password') ?>
	<?= $this->Form->button('Login', ['class' => 'button']) ?>
	<?= $this->Form->end() ?>

</div>
