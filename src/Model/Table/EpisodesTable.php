<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Episodes Model
 *
 * @property \App\Model\Table\FeedsTable|\Cake\ORM\Association\BelongsTo $Feeds
 *
 * @method \App\Model\Entity\Episode get($primaryKey, $options = [])
 * @method \App\Model\Entity\Episode newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Episode[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Episode|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Episode|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Episode patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Episode[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Episode findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class EpisodesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('episodes');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Feeds', [
            'foreignKey' => 'feed_id',
            'joinType' => 'INNER'
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
            ->scalar('title')
            ->maxLength('title', 255)
            ->allowEmpty('title');

        $validator
            ->scalar('description')
            ->maxLength('description', 16777215)
            ->allowEmpty('description');

        $validator
            ->scalar('link')
            ->maxLength('link', 255)
            ->allowEmpty('link');

        $validator
            ->scalar('guid')
            ->maxLength('guid', 255)
            ->allowEmpty('guid');

        $validator
            ->scalar('author')
            ->maxLength('author', 255)
            ->allowEmpty('author');

        $validator
            ->integer('duration')
            ->allowEmpty('duration');

        $validator
            ->integer('played')
            ->allowEmpty('played');

        $validator
            ->boolean('is_favorite')
            ->allowEmpty('is_favorite');

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
        $rules->add($rules->existsIn(['feed_id'], 'Feeds'));

        return $rules;
    }
}
