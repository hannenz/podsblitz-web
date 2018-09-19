<?php
namespace App\View\Helper;

use Cake\View\Helper;

class ButtonHelper extends Helper {
	public $helpers = [ 'Html' ];


	public $spriteUrl = '/dist/img/ikonate.svg';


	public function button($title, $icon = null, $target, $options = []) {
		$defaultOptions = [
		];
		$options = array_merge($defaultOptions, $options);

		if (isset($options['class'])) {
			$options['class'] = str_replace('button', '', $options['class']);
			$options['class'] .= ' button';
		}
		else {
			$options['class'] = 'button';
		}

		$iconStr = '';
		if ($icon != null) { 
			$iconStr = sprintf('<svg class="icon"><use xlink:href="%s#%s"></use></svg>', $this->spriteUrl, $icon);
			$options['escape'] = false;
		}

		return $this->Html->link($iconStr . $title, $target, $options);
	}
	}
