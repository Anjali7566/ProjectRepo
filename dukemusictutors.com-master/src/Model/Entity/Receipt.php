<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Receipt Entity
 *
 * @property int $id
 * @property int $reimbursement_id
 * @property int $document_id
 * @property float $amount
 * @property bool $approved
 *
 * @property \App\Model\Entity\Reimbursement $reimbursement
 * @property \App\Model\Entity\Document $document
 */
class Receipt extends Entity
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
        'amount' => true,
        'approved' => true,
        'reimbursement_id' => true,
        'document_id' => true,

        'document' => true,
    ];
}
