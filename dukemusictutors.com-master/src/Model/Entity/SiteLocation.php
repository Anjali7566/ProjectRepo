<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SiteLocation Entity
 *
 * @property int $id
 * @property int $volunteer_site_id
 * @property int $location_id
 *
 * @property \App\Model\Entity\VolunteerSite $volunteer_site
 * @property \App\Model\Entity\Location $location
 */
class SiteLocation extends Entity
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
        'volunteer_site_id' => true,
        'location_id' => true,
        'volunteer_site' => true,
        'location' => true
    ];
}
