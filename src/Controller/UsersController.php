<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
  function beforeFilter(EventInterface $event)
  {
    parent::beforeFilter($event);

    $this->Authentication->allowUnauthenticated(['login', 'add']);
  }

  /**
   * Index method
   *
   * @return \Cake\Http\Response|null|void Renders view
   */
  public function index()
  {
    $users = $this->Users->find('all')->first();
    $this->Authorization->authorize($users);
    $users = $this->paginate($this->Users);

    $this->set(compact('users'));
  }

  /**
   * View method
   *
   * @param string|null $id User id.
   * @return \Cake\Http\Response|null|void Renders view
   * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
   */
  public function view($id = null)
  {
    $user = $this->Users->get($id, [
      'contain' => ['Listings', 'Notifications'],
    ]);
    $this->Authorization->authorize($user);

    $this->set(compact('user'));
  }

  /**
   * Add method
   *
   * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
   */
  public function add()
  {
    $this->Authorization->skipAuthorization();

    $user = $this->Users->newEmptyEntity();
    if ($this->request->is('post')) {
      $user = $this->Users->patchEntity($user, $this->request->getData());
      if ($this->Users->save($user)) {
        $this->Flash->success(__('The user has been saved.'));

        return $this->redirect(['action' => 'index']);
      }
      $this->Flash->error(__('The user could not be saved. Please, try again.'));
    }
    return $this->redirect(['action' => 'login']);
  }

  /**
   * Edit method
   *
   * @param string|null $id User id.
   * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
   * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
   */
  public function edit($id = null)
  {
    $user = $this->Users->get($id, [
      'contain' => [],
    ]);
    $this->Authorization->authorize($user);
    if ($this->request->is(['patch', 'post', 'put'])) {
      $user = $this->Users->patchEntity($user, $this->request->getData());
      if ($this->Users->save($user)) {
        $this->Flash->success(__('The user has been saved.'));

        return $this->redirect(['action' => 'index']);
      }
      $this->Flash->error(__('The user could not be saved. Please, try again.'));
    }
    $this->set(compact('user'));
  }

  /**
   * Delete method
   *
   * @param string|null $id User id.
   * @return \Cake\Http\Response|null|void Redirects to index.
   * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
   */
  public function delete($id = null)
  {
    $this->request->allowMethod(['post', 'delete']);
    $user = $this->Users->get($id);
    $this->Authorization->authorize($user);

    if ($this->Users->delete($user)) {
      $this->Flash->success(__('The user has been deleted.'));
    } else {
      $this->Flash->error(__('The user could not be deleted. Please, try again.'));
    }

    return $this->redirect(['action' => 'index']);
  }

  public function login()
  {
    $this->Authorization->skipAuthorization();

    $result = $this->Authentication->getResult();
    // If the user is logged in send them away.
    if ($result->isValid()) {
      return $this->redirect([
        'controller' => 'Listings',
        'action' => 'index',
      ]);
    }
    if ($this->request->is('post') && !$result->isValid()) {
      $this->Flash->error('Invalid username or password');
    }

    $user = $this->Users->newEmptyEntity();
    $this->set(compact('user'));
  }

  public function logout()
  {
    $this->Authorization->skipAuthorization();

    $this->Authentication->logout();
    return $this->redirect(['controller' => 'Users', 'action' => 'login']);
  }
}
