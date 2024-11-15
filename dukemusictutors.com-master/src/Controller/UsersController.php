<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    public function initialize() {
        parent::initialize();
        $this->Auth->allow(['login', 'logout', 'create', 'index']);
    }

    //personal account page
    public function index() {
        $user = $this->getUser();
        $this->set(compact('user')); 
    }

    private function getUser() {
        $user = $this->Auth->user();
        if ($user)
            return $this->Users->get($user['id'], ['contain' => ['Privileges']]);
        return $user;
    }

    public function login() {
        if ($this->Auth->user()) {
            $this->Flash->error("You're already logged in.");
            return $this->redirect($this->referer());
        }
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                if ($user['inactive'] || !$user['approved'])
                    $this->Flash->error('This account is inactive or unapproved.');
                else {
                    $this->Auth->setUser($user);
                    $this->Flash->success("You are now logged in");
                    return $this->redirect($this->Auth->redirectUrl());
                }
            }
            else
                $this->Flash->error('Your username or password is incorrect.');
        }
    }

    public function logout() {   
        if ($this->Auth->user())
            $this->Flash->success('You are now logged out.');
        else 
            $this->Flash->error("You aren't logged in. Can't log out.");
        return $this->redirect($this->Auth->logout());
    }

    public function create() {
        $user = $this->Users->newEntity();
        $location = $this->Users->Locations->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            $location = $this->Users->Locations->patchEntity($location, $this->request->getData());
            $user->location = $location;
            $success = $this->Users->getConnection()->transactional(function ($conn) use ($user) {
                $userSaved = $this->Users->save($user, ['associated' => 'Locations']);
                if ($userSaved !== false) {
                    $privileges = $this->Users->Privileges->newEntity();
                    $privileges->user_id = $user->id;
                    $userSaved = $this->Users->Privileges->save($privileges);
                }
                return $userSaved !== false;
            });
            if ($success) {
                $this->Flash->success(__("Account created. We'll approve your account shortly."));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user', 'location'));
    }

    //personal
    public function view($id = null) {
        //FIXME do this, update privileges
        $user = $this->Users->get($id, [
            'contain' => ['Locations']
        ]);

        $this->set('user', $user);
    }

    //personal
    public function edit($id = null) {
        //FIXME do this, remove view
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $locations = $this->Users->Locations->find('list', ['limit' => 200]);
        $this->set(compact('user', 'locations'));
    }

     //FIXME test
    public function makeInactive($id = null) {
        $this->request->allowMethod(['post']);
        //FIXME make sure current user is an active, approved member editor 
        //FIXME make sure isn't affecting themself??

        $user = $this->Users->get($id, ['contain' => 'Privileges']);
        if ($user->privilege->member_editor) {
            $numMemberEditors = $memberEditors = $this->Users->find()
                ->where(['inactive' => false])
                ->where(['approved' => true])
                ->innerJoinWith('Privileges', function ($query) {
                    return $query->where(['Privileges.member_editor' => true]);
                })
                ->count();
            $this->log($numMemberEditors, 'debug'); //FIXME remove
            if ($numMemberEditors <= 1)
                $this->Flash->error("Can't make the only member editor inactive");
            else {
                $user->inactive = true;
                $this->Users->save($user); //FIXME make sure it works
            }
        }

        return $this->redirect(['action' => 'index']);
    }

    //FIXME todo
    public function approve() {
        
    }

    public function members() {
        $user = $this->getUser();
        $allowed = $user && !$user->inactive && $user->approved && $user->privilege->member_editor;
        if (!$allowed) {
            $this->Flash->warning("You're not allowed to view this page.");
            return $this->redirect($this->referer());
        }
        $users = $this->paginate($this->Users);
        $this->set(compact('users'));
    }

    public function editMember() {
        //FIXME todo
    }

    public function viewMember() {
        //FIXME todo?
    }
}
