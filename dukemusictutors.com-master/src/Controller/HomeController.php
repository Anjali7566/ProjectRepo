<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Events Controller
 *
 * @property \App\Model\Table\EventsTable $Events
 *
 * @method \App\Model\Entity\Event[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class HomeController extends AppController
{
    public function initialize()
    {
        parent::initialize();

        $this->loadModel('VolunteerSites');
        $this->loadModel('Events');

        $this->Auth->allow(['index']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index() 
    {
        $sites = $this->VolunteerSites
            ->find('all', ['limit' => 10])
            ->contain(['Documents', 'Locations'])
            ->toArray();
        $events = $this->Events
            ->find('all', ['limit' => 10])
            ->contain(['Documents'])
            ->order(['date' => 'ASC'])
            ->toArray();//FIXME make everything soft deleted and add condition 'where not soft deleted'
        $this->set(compact('sites', 'events'));
    }
}