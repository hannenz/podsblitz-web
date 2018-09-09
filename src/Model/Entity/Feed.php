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
		'user' => true,
		'published' => true
	];



	public function __construct($properties = [], $options = []) {
		parent::__construct($properties, $options);
		return;

	}



	/**
	 * Update episodes from Feed
	 *
	 * @access public
	 * @return void
	 */
	public function syncEpisodes() {

		$maxItems = 10;
		$EpisodesTable = TableRegistry::get('Episodes');
		$this->items = [];

		if (!empty($this->url)) {
			$feedXml = file_get_contents($this->url);
			$xml = Xml::build($feedXml);
			$items = $xml->xpath('channel/item');

			$n = 0;
			foreach ($items as $item) {
				if (empty((array)$item[0])) {
					continue;
				}

				if ($n++ > $maxItems) {
					break;
				}

				$episode = $EpisodesTable
					->find('all')
					->where([
						'guid' => $item->guid,
						'feed_id' => $this->id
					])
					->first()
				;
				if (empty($episode)) {
					$episode = $EpisodesTable->newEntity();
				}

				$posterFilename = '';
				$posterUrl = false;
				$itunes = $item->children('http://www.itunes.com/dtds/podcast-1.0.dtd');

				// foreach ($itunes->image->attributes('http://www.itunes.com/dtds/podcast-1.0.dtd') as $attr => $value) {
				$attributes = $itunes->image->attributes();
				if (is_array($attributes) && count($attributes) > 0) {
					foreach ((array)$attributes as $attr => $value) {
						if ($attr == 'href') {
							// Download poster image
							$posterUrl = (string)$value;
							break;
						}
					}
				}

				if (!empty($posterUrl) && preg_match('%https?\://.*\.(.*)$%', $posterUrl, $matches)) {
					$extension = $matches[1];
					$posterFilename = WWW_ROOT . 'media' . DS . uniqid() . '.' . $extension;
					$options = [
						CURLOPT_FILE => fopen($posterFilename, 'w'),
						CURLOPT_TIMEOUT => 60,
						CURLOPT_URL => $posterUrl
					];
					$ch = curl_init();
					curl_setopt_array($ch, $options);
					curl_exec($ch);
					curl_close($ch);
				}


				$EpisodesTable->patchEntity($episode, [
					'title' => (string)$item->title,
					'description' => (string)$item->description,
					'guid' => (string)$item->guid,
					'link' => (string)$item->link,
					'author' => (string)$itunes->author,
					'feed_id' => $this->id,
					'filetype' => (string)$item->enclosure['type'],
					'filesize' => (int)$item->enclosure['length'],
					'fileurl' => (string)$item->enclosure['url'],
					'duration' => (string)$itunes->duration,
					'published' => new \Cake\I18n\Time((string)$item->pubDate),
					'poster' => str_replace(WWW_ROOT, '', $posterFilename)
				]);
				// $episode->published = new \Cake\I18n\Time((string)$item->pubDate);


				$EpisodesTable->save($episode);
				// if (!$result) {
				// 	debug($episode->getErrors());
				// 	die();
				// }
			}
		}
	}
}
