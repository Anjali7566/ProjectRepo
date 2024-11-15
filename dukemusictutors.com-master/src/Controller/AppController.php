<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\ORM\Table;
use \Cake\Http\Response;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);
        $this->loadComponent('Flash');
        
        $this->loadComponent('Auth', [
            'authenticate' => [
                'Form' => [
                    'fields' => [
                        'username' => 'username',
                        'password' => 'password'
                    ]
                ]
            ],
            'authError' => 'Please sign in to access that location.',
            'loginAction' => [
                'controller' => 'Users',
                'action' => 'index'
            ],
            'unauthorizedRedirect' => $this->referer(),  
        ]);

        //use this in every controller where something doesn't need authentication
        //$this->Auth->allow(['actions']);

        //FIXME use this
        /*
         * Enable the following component for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
    }

    //@throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
    protected function hardDelete(Table $table, int $id, Response $redirection, $itemName) {
        $successMessage = __('The '.$itemName.' has been deleted.');
        $errorMessage = __('The '.$itemName.' could not be deleted. Please, try again.');
        $this->request->allowMethod(['post', 'delete']);
        $item = $table->get($id);
        if ($table->delete($item))
            $this->Flash->success($successMessage);
        else
            $this->Flash->error($errorMessage);
    }
}
