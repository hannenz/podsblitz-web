<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Utility\Xml;
use Cake\ORM\TableRegistry;
use Cake\Http\Client;

/**
 * Feed Entity
 *
 * @property int $id
 * @property string $url
 * @property string $title
 * @property string $poster
 * @property int $user_id
 *
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
		'description' => true,
		'poster' => true,
		'user_id' => true,
		'user' => true,
		'link' => true,
		'language' => true
	];



	public function __construct($properties = [], $options = []) {
		parent::__construct($properties, $options);
		return;

	}



	/**
	 * Fetch feed data from its URL
	 *
	 * @param string		The feed's url
	 * @return void
	 * @throws Exception
	 */
	public function fetchFromUrl($url) {

		try {
			$xml = $this->fetchXml();
		}
		catch(\Exception $e) {
			// TODO: error handling
		}

		$this->url = $url;
		$this->title = (string)$xml->channel->title;
		$this->description = (string)$xml->channel->description;
		$this->link = (string)$xml->channel->link;
		$this->published = new \Cake\I18n\Time((string)$xml->channel->pubDate);

		$this->fetchPoster();
	}



	public function fetchXml() {

		if (empty($this->url)) {
			throw new \Exception('No feed url available');
		}

		$http = new Client();
		$response = $http->get($this->url);
		try {
			$xml = Xml::build($response->body);
		}
		catch (\Cake\Utility\Exception\XmlException $e) {
			throw new \Exception(sprintf("Xml::build failed"));
		}
		return $xml;
	}



	public function fetchPoster() {

		$xml = $this->fetchXml();
		$imageUrl = (string)$xml->channel->image->url;
		if (preg_match('%^https?\://.*(\..*)$%', $imageUrl, $matches)) {

			$extension = $matches[1];
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $imageUrl);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
			$data = curl_exec($ch);
			$error = curl_error($ch);
			curl_close($ch);

			$dest = WWW_ROOT . "media" . DS . uniqid() . $extension;
			if (@file_put_contents($dest, $data) === false) {
				throw new \Exception("file_put_contents() failed");
			}

			$this->poster = str_replace(WWW_ROOT, '' , $dest);
		}
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
						'guid' => (string)$item->guid,
						'feed_id' => $this->id
					])
					->first()
				;
				if (empty($episode)) {
					$episode = $EpisodesTable->newEntity();
				}

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

				// If no itunes image, then search in description for an html img tag
				if (empty($posterUrl)) {
					if (preg_match('/\<img.*src\=\"(.*?)[\?\&\#].*\"/', (string)$item->description, $matches)) {
						$posterUrl = $matches[1];
					}
				}

				$posterFilename = '';
				if (!empty($posterUrl) && preg_match('%https?\://.*\.(.*)$%', $posterUrl, $matches)) {
					$extension = $matches[1];
					$posterFilename = WWW_ROOT . 'media' . DS . sha1((string)$item->guid) . '.' . $extension;
					if (!file_exists($posterFilename)) {
						// $posterFilename = WWW_ROOT . 'media' . DS . uniqid() . '.' . $extension;
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
				}

				// Duration: convert hh:mm:ss string to int (seconds)
				$duration = 0;
				$str = (string)$itunes->duration;
				// if (preg_match('/(\d{1,2}\:)?(\d{1,2}\:)?(\d{1,2})?/', $str, $matches)) {
				if (preg_match('/^(?:(?:([01]?\d|2[0-3]):)?([0-5]?\d):)?([0-5]?\d)$/', $str, $matches)) {
					$hours = (int)$matches[1];
					$minutes = (int)$matches[2];
					$seconds = (int)$matches[3];
					$duration = 3600 * $hours + 60 * $minutes + $seconds;
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
					'duration' => $duration,
					'published' => new \Cake\I18n\Time((string)$item->pubDate),
					'poster' => str_replace(WWW_ROOT, '', $posterFilename)
				]);
				// $episode->published = new \Cake\I18n\Time((string)$item->pubDate);


				$result = $EpisodesTable->save($episode);
				if (!$result) {
					debug($episode->getErrors());
					die();
				}
			}
		}
	}
}
