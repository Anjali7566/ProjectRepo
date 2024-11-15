<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;
use Cake\I18n\FrozenTime;

/**
 * Reimbursements Controller
 *
 * @property \App\Model\Table\ReimbursementsTable $Reimbursements
 *
 * @method \App\Model\Entity\Reimbursement[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ReimbursementsController extends AppController
{
    public function initialize()
    {
        parent::initialize();

        $this->loadModel('Users');
        $this->loadModel('Receipts');
        $this->loadModel('OtherRiders');
        $this->loadModel('Documents');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {   
        $query = $this->getReimbursements($this->Auth->user('id'));
        $this->paginate = ['contain' => ['VolunteerSites']];
        $reimbursements = $this->paginate($query);
        $this->set(compact('reimbursements'));
    }

    private function getReimbursements($userID) {
        $baseQuery = $this->Reimbursements->find();
        if (!is_null($userID))
            $baseQuery = $baseQuery->where(['Reimbursements.user_id' => $userID]);
        return $baseQuery->contain(['Users', 'VolunteerSites', 'Receipts']);
    }

    public function toggleSubmission($id = null) {
        if (!$this->isTreasurer()) {
            $this->Flash->error("You don't have this permission.");
        }
        else {
            $reimbursement = $this->Reimbursements->get($id);
            $shouldSubmit = !$reimbursement->submitted;
            $reimbursement->submitted = $shouldSubmit ? new FrozenTime() : null;
            if ($this->Reimbursements->save($reimbursement)) 
                $this->Flash->success("Reimbursement marked as " . ($shouldSubmit ? 'submitted.' : 'unsubmitted.'));
            else
                $this->Flash->error("Reimbursement submission status couldn't be updated.");
        }
        return $this->redirect($this->referer());
    }

    /**
     * All method
     *
     * @return \Cake\Http\Response|null
     */
    public function all() {
        $this->request->allowMethod(['post', 'get']);
        if (!$this->isTreasurer()) {
            $this->Flash->error(__("Only the treasurer can access this location."));
            return $this->redirect($this->referer());
        }
        $userID = null;
        if ($this->request->is('post')) {
            $requestedID = $this->request->getData('user_id');
            if ($requestedID !== null && $requestedID > 0) {
                $userID = $requestedID;
            }
        }
        $query = $this->getReimbursements($userID);
        $this->paginate = [
            'contain' => ['Users', 'VolunteerSites']
        ]; //FIXME make contain included in method above?
        $reimbursements = $this->paginate($query);
        $allUsers = $this->Users
            ->find('list')
            ->where(['Users.id !=' => 1]);
        $this->set(compact('reimbursements', 'allUsers'));
    }

    /**
     * View method
     *
     * @param string|null $id Reimbursement id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        if ($id != null) {
            $reimbursement = $this->Reimbursements->get($id, [
                'contain' => ['VolunteerSites', 'OtherRiders', 'OtherRiders.Users', 'Receipts', 'Receipts.Documents', 'Users', 'Users.Locations']
            ]);
            if (!\is_null($reimbursement) && $this->ownerOrTreasurer($reimbursement)) {
                $isTreasurer = $this->isTreasurer();
                $this->set(compact('reimbursement', 'isTreasurer'));
            }
            else {
                $this->Flash->error(__("You don't have permissions to view this reimbursement"));
                return $this->redirect($this->referer());
            }
        }
        else {
            $this->Flash->error(__("Reimbursement doesn't exist"));
            return $this->redirect($this->referer());
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Reimbursement id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    // public function edit($id = null)
    // {
    //     $reimbursement = $this->Reimbursements->get($id, [
    //         'contain' => []
    //     ]);
    //     if ($this->request->is(['patch', 'post', 'put'])) {
    //         $reimbursement = $this->Reimbursements->patchEntity($reimbursement, $this->request->getData());
    //         if ($this->Reimbursements->save($reimbursement)) {
    //             $this->Flash->success(__('The reimbursement has been saved.'));

    //             return $this->redirect(['action' => 'index']);
    //         }
    //         $this->Flash->error(__('The reimbursement could not be saved. Please, try again.'));
    //     }
    //     $users = $this->Reimbursements->Users->find('list', ['limit' => 200]);
    //     $volunteerSites = $this->Reimbursements->VolunteerSites->find('list', ['limit' => 200]);
    //     $this->set(compact('reimbursement', 'users', 'volunteerSites'));
    // }

    /**
     * Delete method
     *
     * @param string|null $id Reimbursement id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        //FIXME make sure its the user's reimbursement or the treasurer
        $reimbursement = $this->Reimbursements->get($id);
        if (!$reimbursement->submitted && !$reimbursement->approved && $this->ownerOrTreasurer($reimbursement))
            $this->hardDelete($this->Reimbursements, $id, $this->redirect($this->referer()), 'reimbursement');
    }

    /**
    * Add method
    *
    * @return \Cake\Http\Response|null Redirects to index.
    */
    public function add() {
        $maxNumReceipts = 4;
        $reimbursement = $this->Reimbursements->newEntity();
        $documents = $this->newEntities($this->Documents, $maxNumReceipts);
        $receipts = $this->newEntities($this->Receipts, $maxNumReceipts);

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $numReceipts = $data['numreceipts'];
            $includedDocuments = \array_slice($documents, 0, $numReceipts);
            $includedReceipts = \array_slice($receipts, 0, $numReceipts);

            $namesUpdated = $this->updateDocNames($data['documents'], $data['date_string']);
            $this->patchAll($this->Documents, $includedDocuments, $data['documents'], true);
            $this->patchAll($this->Receipts, $includedReceipts, $data['receipts'], false); //still patch for validation errors

            $saveSuccess = false;
            $saveSuccess = $this->saveDocuments($includedDocuments);
            if ($saveSuccess) {
                foreach ($includedReceipts as $k => $receipt)
                    $receipt->document_id = $includedDocuments[$k]->id;

                $saveSuccess = $this->createReimbursement($data, $reimbursement, $includedReceipts);
                if ($saveSuccess === false) {
                    foreach ($includedDocuments as $document)
                        $this->Documents->delete($document);
                }
            }

            if ($saveSuccess) {
                $this->Flash->success(__('The reimbursement has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            else {
                $this->log("Couldn't save reimbursement. Reimbursement, then document errors: ", 'debug');
                $this->log($reimbursement->getErrors(), 'debug');
                foreach ($includedDocuments as $document)
                    $this->log($document->getErrors(), 'debug');
                $this->log("data: ", 'debug');
                $this->log($data, 'debug');
                $this->Flash->error(__("The reimbursement couldn't be saved."));
            }
        }

        $volunteerSites = $this->Reimbursements->VolunteerSites->find('list');
        //FIXME add selected site using SiteUsers
        $extraRiders = $this->Reimbursements->Users->find('list')
            ->where(['Users.id !=' => 1])
            ->where(['Users.id !=' => $this->Auth->user('id')]);
        $this->set(compact('reimbursement', 'documents', 'receipts', 'volunteerSites', 'extraRiders'));
    }

    private function newEntities($table, $num) {
        $entities = [];
        for ($k = 0; $k < $num; $k++)
            $entities[] = $table->newEntity();
        return $entities;
    }

    private function updateDocNames(&$docData, $dateString) {
        $newDocName = $this->Reimbursements->getDocumentDate($dateString);
        $this->log($newDocName ? 'yes' : 'no', 'debug'); //FIXME remove
        if ($newDocName === false)
            return false;
        $user = $this->Auth->user();
        $newDocName .= '-' . $user['first_name'] . '-' . $user['last_name'] . '-';
        for ($k = 0; $k < count($docData); $k++) {
            $docData[$k]['filestuff']['new_name'] = $newDocName . ($k + 1);
        }
        return true;
    }

    private function patchAll($table, &$entities, $data, $areDocuments = false) {
        $options = $areDocuments ? ['validate' => 'receipt'] : [];
        for ($k = 0; $k < count($entities); $k++)
            $entities[$k] = $table->patchEntity($entities[$k], $data[$k], $options);
    }

    private function saveDocuments($documents) {
        foreach ($documents as $doc) {
            $doc->folder_id = 2; //FIXME must align with server
            $doc->user_id = $this->Auth->user('id');
        }
        return $this->Documents->getConnection()->transactional(function($connection) use ($documents) {
            $success = true;
            foreach ($documents as $document) 
                $success = $success && $this->Documents->save($document);
            return $success;
        });
    }

    private function createReimbursement($data, $reimbursement, $receipts) {
        $this->updateOtherRiderData($data);
        $reimbursement = $this->Reimbursements->patchEntity($reimbursement, $data, ['associated' => 'OtherRiders']);
        $reimbursement->receipts = $receipts;
        $reimbursement->user_id = $this->Auth->user('id');
        return $this->Reimbursements->save($reimbursement, ['associated' => ['OtherRiders', 'Receipts']]);
    }

    private function updateOtherRiderData(&$data) {
        $otherRiderData = &$data['other_riders'];
        $otherRiderIDs = $otherRiderData['user_ids'];
        if (!is_array($otherRiderIDs))
            return;
        foreach ($otherRiderIDs as $num => $id)
            $otherRiderData[$num]['user_id'] = $id;
        unset($otherRiderData['user_ids']);
    }

    //FIXME very inefficient
    private function ownerOrTreasurer($reimbursement) {
        return $this->Auth->user('id') === $reimbursement->user_id || $this->isTreasurer();
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
        return $this->Reimbursements->Users
            ->find()
            ->where(['user_id' => $this->Auth->user('id')])
            ->contain(['Privileges'])
            ->first();
    }
}
