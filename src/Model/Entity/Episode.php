<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Episode Entity
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $link
 * @property string $guid
 * @property string $author
 * @property int $duration
 * @property int $feed_id
 * @property int $played
 * @property bool $is_favorite
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Feed $feed
 */
class Episode extends Entity
{

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
        'title' => true,
        'description' => true,
        'link' => true,
        'guid' => true,
        'author' => true,
        'duration' => true,
        'feed_id' => true,
        'played' => true,
        'is_favorite' => true,
        'created' => true,
        'modified' => true,
        'feed' => true
    ];
}
