<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SiteUser Entity
 *
 * @property int $volunteer_site_id
 * @property int $user_id
 *
 * @property \App\Model\Entity\VolunteerSite $volunteer_site
 * @property \App\Model\Entity\User $user
 */
class SiteUser extends Entity
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
        'volunteer_site' => true,
        'user' => true
    ];
}
