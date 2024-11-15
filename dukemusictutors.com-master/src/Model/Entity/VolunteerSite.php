<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * VolunteerSite Entity
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $document_id
 * @property string|null $website
 *
 * @property \App\Model\Entity\Document $document
 */
class VolunteerSite extends Entity
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
        'name' => true,
        'document_id' => true,
        'website' => true,
        'document' => true
    ];
}
