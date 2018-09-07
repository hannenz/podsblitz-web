<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Xml;
use Cake\ORM\TableRegistry;

/**
 * Feed Entity
 *
 * @property int $id
 * @property string $url
 * @property string $title
 * @property string $poster
 * @property int $user_id
 *
 * @property \App\Model\Entity\User $user
 */
class Feed extends Entity {

	/**
	 * Fields that can be mass assigned using newEntity() or patchEntity().
	 *
	 * Note that when '*' is set to true, this allows all unspecified fields to
	 * be mass assigned. For security purposes, it is advised to set '*' to false
	 * (or remove it), and explicitly make individual fields accessible as needed.
	 *
	 * @var array
	 */
	protected $_accessible = [
		'url' => true,
		'title' => true,
		'poster' => true,
		'user_id' => true,
		'user' => true
	];



	public function __construct($properties = [], $options = []) {
		parent::__construct($properties, $options);

		$this->items = [];

		if (!empty($this->url)) {
			$feedXml = file_get_contents($this->url);
			$xml = Xml::build($feedXml, ['return' => 'simplexml']);
			$this->items = $xml->xpath('channel/item');
			// $this->syncEpisodes();
		}
	}



	/**
	 * Initially fetch a feed from its URL
	 * e.g. Fetch image and description etc.
	 * First time sync episodes
	 *
	 * @param string 	url
	 */
	public function fetch($url) {
		$feedXml = file_get_contents($url);
		$xml = Xml::build($feedXml);
		$imageObjects = $xml->xpath('channel/image');
		// Download image, save it

		printf('<img width="200" src="%s" />', $imageObjects[0]->url);
		die ();
	}


	/**
	 * Update episodes from Feed
	 *
	 * @access public
	 * @return void
	 */
	public function syncEpisodes() {

		$EpisodesTable = TableRegistry::get('Episodes');

		foreach ($this->items as $item) {
			$episode = $EpisodesTable->findByGuid($item->guid)->first();
			if (empty($episode)) {
				$episode = $EpisodesTable->newEntity();
			}
			$EpisodesTable->patchEntity($episode, (array)$item);
			$episode->feed_id = $this->id;
			$EpisodesTable->save($episode);
		}
	}
}
