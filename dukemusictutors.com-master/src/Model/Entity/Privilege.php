<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Privilege Entity
 *
 * @property int $id
 * @property int $user_id
 * @property bool $member_editor
 * @property bool $treasurer
 * @property bool $site_manager
 * @property bool $event_editor
 * @property bool $moderator
 * @property bool $permanent_deleter
 * @property bool $email_list_accessor
 *
 * @property \App\Model\Entity\User $user
 */
class Privilege extends Entity
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
        'user_id' => true,
        'member_editor' => true,
        'treasurer' => true,
        'site_manager' => true,
        'event_editor' => true,
        'moderator' => true,
        'permanent_deleter' => true,
        'email_list_accessor' => true,
        'user' => true
    ];
}
