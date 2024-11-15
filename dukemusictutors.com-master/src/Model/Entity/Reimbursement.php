<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\I18n\Date;
use Cake\ORM\TableRegistry;
use \Cake\ORM\Query;

/**
 * Reimbursement Entity
 *
 * @property int $id
 * @property int $user_id
 * @property int $volunteer_site_id
 * @property \Cake\I18n\FrozenTime $date
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property \Cake\I18n\FrozenTime|null $submitted
 * @property bool $deleted
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\VolunteerSite $volunteer_site
 */
class Reimbursement extends Entity
{
    private $receipts;

    //protected $_virtual = ['total', 'approved_total', 'approved'];

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
        'user_id' => false,
        'volunteer_site_id' => true,
        'date' => true,
        'created' => true,
        'modified' => true,
        'submitted' => true,
        'deleted' => true,

        'receipts' => true,
        'other_riders' => true,
        
        'user' => false,
        'volunteer_site' => false
    ];

    //FIXME remove?? or make trait that goes along with SimpleDateBehavior??
    protected function _getDateString() {
        if (isset($this->_properties['date_string'])) {
            return $this->_properties['date_string'];
        }
        if (empty($this->date)) {
            return '';
        }
        $date = new Date($this->date);
        return $date->i18nFormat("M/d");
    }

    //FIXME make ternary (like no, partially, yes)
    protected function _getApproved() {
        $approved = true;
        if ($this->getReceipts()) {
            foreach ($this->receipts as $receipt)
                $approved = $approved & $receipt->approved;
        }
        return $approved;
    }

    protected function _getTotal() {
        $total = 0;
        if ($this->getReceipts()) {
            foreach ($this->receipts as $receipt)
                $total += $receipt->amount;
        }
        return $total;
    }

    protected function _getApprovedTotal() {
        $total = 0;
        foreach ($this->getReceipts() as $receipt) {
            if ($receipt->approved)
                $total += $receipt->amount;
        }
        return $total;
    }

    private function getReceipts() {
        if (!is_null($this->receipts))
            return $this->receipts;

        $this->receipts = [];
        $prop = $this->_properties;
        if (isset($prop['id'])) {
            $this->receipts = TableRegistry::get('Receipts') //FIXME make receipts table static??
                ->find()
                ->where(['reimbursement_id' => $prop['id']])
                ->toArray();
        }
        return $this->receipts;
    }
}
