<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

/**
 * Messages Controller
 *
 * @property \App\Model\Table\MessagesTable $Messages
 * @method \App\Model\Entity\Message[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MessagesController extends AppController
{
    function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        // $this->Authentication->allowUnauthenticated([]);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users'],
        ];
        $messages = $this->Messages->find("all")
        ->where([
            "or" =>
            [
                'receiver_id' => $this->request->getAttribute('identity')->id,
                'sender_id' => $this->request->getAttribute('identity')->id
            ]
        ])
        ->contain(['ReceiversUsers', 'Users']);
        $this->Authorization->authorize(empty($messages->first()) ? $this->Messages->newEmptyEntity() : $messages->first());
        $messages = $this->paginate($messages);

        $this->set(compact('messages'));
    }

    /**
     * View method
     *
     * @param string|null $id Message id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $message = $this->Messages->get($id, [
            'contain' => ['Users', 'ReceiversUsers'],
        ]);
        $this->Authorization->authorize($message);

        $messages = $this->Messages->find('all')
            ->where(['OR' => ['Messages.receiver_id' => $this->request->getAttribute('identity')->id, 'Messages.sender_id' => $this->request->getAttribute('identity')->id]])
            ->order(['Messages.created' => 'DESC'])
            ->contain(['ReceiversUsers', 'Users']);

        // Save la nouvelle date "readby" dans la bdd
        if ($message->readby == null && $message->receiver_id == $this->request->getAttribute('identity')->id) {
            $message->readby = date('Y-m-d H:i:s');
            $this->Messages->save($message);
        }

        $this->set(compact('message', 'messages'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $message = $this->Messages->newEmptyEntity();
        $this->Authorization->authorize($message);
        if ($this->request->is('post')) {
            $message = $this->Messages->patchEntity($message, $this->request->getData());
            $message->sender_id = $this->request->getAttribute('identity')->id;
            if ($this->Messages->save($message)) {
                $this->Flash->success(__('The message has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The message could not be saved. Please, try again.'));
        }

        $users = $this->fetchTable('Users')->find("all")
        ->where(['Users.id !=' => $this->request->getAttribute('identity')->id]);
        $usersList = [];

        foreach ($users->toArray() as $key => $value) {
            $usersList[$value->id] = $value->pseudo;
        }
        $this->set(compact('message', 'usersList'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Message id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $message = $this->Messages->get($id);
        $this->Authorization->authorize($message);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $message = $this->Messages->patchEntity($message, $this->request->getData());
            if ($this->Messages->save($message)) {
                $this->Flash->success(__('The message has been saved.'));

                return $this->redirect(['action' => 'view', $message->id]);
            }
            $this->Flash->error(__('The message could not be saved. Please, try again.'));
        }
        $this->set(compact('message'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Message id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $message = $this->Messages->get($id);
        $this->Authorization->authorize($message);
        if ($this->Messages->delete($message)) {
            $this->Flash->success(__('The message has been deleted.'));
        } else {
            $this->Flash->error(__('The message could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
