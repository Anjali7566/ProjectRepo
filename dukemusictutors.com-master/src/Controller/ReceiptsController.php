<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;
use Cake\I18n\FrozenTime;

/**
 * Receipts Controller
 *
 * @property \App\Model\Table\ReceiptsTable $Receipts
 *
 * @method \App\Model\Entity\Receipt[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ReceiptsController extends AppController
{
    public function initialize()
    {
        parent::initialize();

        $this->loadModel('Users');
    }

    public function toggleApproval($id = null) {
        if (!$this->isTreasurer()) {
            $this->Flash->error("You don't have this permission.");
        }
        else {
            $receipt = $this->Receipts->get($id);
            $shouldApprove = !$receipt->approved;
            $receipt->approved = $shouldApprove ? true : false;
            if ($this->Receipts->save($receipt)) 
                $this->Flash->success("Receipt marked as " . ($shouldApprove ? "approved." : "unapproved."));
            else
                $this->Flash->error("Receipt approval couldn't be updated.");
        }
        return $this->redirect($this->referer());
    }

    //FIXME make this a component or something. 
    //FIXME very inefficient
    private function isTreasurer() {
        $currentUser = $this->getCurrentUser();
        return !\is_null($currentUser) && $currentUser->privilege->treasurer;
    }

    //FIXME make this a component or something. 
    //FIXME very inefficient
    private function getCurrentUser() {
        return $this->Users
            ->find()
            ->where(['user_id' => $this->Auth->user('id')])
            ->contain(['Privileges'])
            ->first();
    }
}