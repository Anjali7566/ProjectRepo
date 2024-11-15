<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SiteImage Entity
 *
 * @property int $id
 * @property int $document_id
 * @property int $volunteer_site_id
 * @property int $user_id
 * @property int $public_post_id
 * @property string|null $description
 * @property bool $deleted
 *
 * @property \App\Model\Entity\Document $document
 * @property \App\Model\Entity\VolunteerSite $volunteer_site
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\PublicPost $public_post
 */
class SiteImage extends Entity
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
        'document_id' => true,
        'volunteer_site_id' => true,
        'user_id' => true,
        'public_post_id' => true,
        'description' => true,
        'deleted' => true,
        'document' => true,
        'volunteer_site' => true,
        'user' => true,
        'public_post' => true
    ];
}
