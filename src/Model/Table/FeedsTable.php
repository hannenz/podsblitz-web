<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Xml;

use \App\Model\Entity\Feed;

/**
 * Feeds Model
 *
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\Feed get($primaryKey, $options = [])
 * @method \App\Model\Entity\Feed newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Feed[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Feed|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Feed|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Feed patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Feed[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Feed findOrCreate($search, callable $callback = null, $options = [])
 */
class FeedsTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('feeds');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);

		$this->hasMany('Episodes', [
			'sort' => [
				'published' => 'DESC',
			],
			'limit' => 2

		]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('url')
            ->maxLength('url', 255)
            ->requirePresence('url', 'create')
            ->notEmpty('url');

        $validator
            ->scalar('title')
            ->maxLength('title', 200)
            ->allowEmpty('title');

        $validator
            ->scalar('poster')
            ->maxLength('poster', 255)
            ->allowEmpty('poster');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }



	/**
	 * Import feeds from an opml file
	 *
	 * @param string					Full path to file
	 * @param int						user_id to import the feeds to
	 * @return integer					Nr. of successfully imported feeds
	 */
	public function import($file, $userId) {
		$opml = file_get_contents($file);
		$xml = Xml::build($opml, ['return' => 'simplexml']);
		$feeds = $xml->xpath('*/outline');
		$n = 0;
		foreach ($feeds as $feed) {
			$url = (string)$feed['xmlUrl'];
			if (preg_match('%^https?\://%', $url)) {
				if ($this->subscribe($url, $userId)) {
					$n++;
				}
			}
		}

		return $n;
	}


	/**
	 * Import feeds from an opml file
	 *
	 * @param string					Full path to file
	 * @return Array					Array of App\Model\Entity\Feed
	 */
	public function getFeedsFromOpml($file) {
		$opml = @file_get_contents($file);
		$xml = Xml::build($opml);
		$outlines = $xml->xpath('*/outline');

		$results = [];
		foreach ($outlines as $outline) {

			$url = (string)$outline['xmlUrl'];

			if (preg_match('%^https?\://%', $url)) {

				$feed = $this->newEntity();
				$feed->fetchFromUrl($url);

				$results[] = $feed;
			}
		}

		return $results;
	}


	/**
	 * Add a new feed as subscription for current user
	 *
	 * @param string					The feed's url
	 * @param int						user_id to subscribe this feed to
	 * @return boolean					Success
	 */
	public function subscribe($url, $userId) {

		// Check if user has subscribed to this url yet
		$query = $this->find('all')
				 ->where([
					 'url' => $url,
					 'user_id' => $userId
				 ]);
		$n = $query->count();
		if ($n === 0) {

			// Get the feed's xml
			$feedXml = @file_get_contents($url);
			if (!$feedXml) {
				// TODO: Error handling!
			}

			$xml = Xml::build($feedXml);
			$feedData = $xml->channel;


			// Create the new feed's entity and persist it
			// TODO: Fetch the image and save it in a web friendly compression / format


			// Download poster image
			$posterUrl = (string)$feedData->image->url;
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

			$feed = $this->newEntity([
				'url' => $url,
				'title' => (string)$feedData->title,
				'description' => (string)$feedData->description,
				'poster' => str_replace(WWW_ROOT, '', $posterFilename),
				'user_id' => $userId
			]);

			if (!$this->save($feed)) {
				debug($feed->getErrors());
				die();
			}

			$feed->syncEpisodes();
			return true;
		}
	}
}
