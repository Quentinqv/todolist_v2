<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Notifications Controller
 *
 * @property \App\Model\Table\NotificationsTable $Notifications
 * @method \App\Model\Entity\Notification[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class NotificationsController extends AppController
{

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $notification = $this->Notifications->newEmptyEntity();
        $this->Authorization->authorize($notification);
        if ($this->request->is('post')) {
            $notification = $this->Notifications->patchEntity($notification, $this->request->getData());
            if ($this->Notifications->save($notification)) {
                $this->Flash->success(__('The notification has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The notification could not be saved. Please, try again.'));
        }
        $users = $this->Notifications->Users->find('list', ['limit' => 200])->all();
        $this->set(compact('notification', 'users'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Notification id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $notification = $this->Notifications->get($this->request->getData('id'));
        $this->Authorization->authorize($notification);
        if ($this->Notifications->delete($notification)) {
            // Get count of all no readby notifications from current user
            $unreadnotifs = $this->Notifications->find('all')
                ->where(['readby IS NULL'])
                ->where(['user_id' => $this->request->getAttribute('identity')->id])
                ->count();

            $notifs = $this->Notifications->find('all')
                ->where(['readby IS NULL'])
                ->where(['user_id' => $this->request->getAttribute('identity')->id])
                ->count();

            $this->response = $this->response->withStringBody(json_encode(['success' => true, 'nbUnread' => $unreadnotifs, 'nbNotifs' => $notifs]));
        } else {
            $this->response = $this->response->withStringBody(json_encode(['success' => false]));
        }

        $this->response = $this->response->withType('json');
        return $this->response;
    }

    public function seen ($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $notification = $this->Notifications->newEmptyEntity();
        $notification = $this->Notifications->patchEntity($notification, $this->request->getData());
        $notification = $this->Notifications->get($this->request->getData('id'));
        $this->Authorization->authorize($notification);

        if ($notification->readby == null) {
            $notification->readby = date('Y-m-d H:i:s');
        } else {
            $notification->readby = null;
        }

        if ($this->Notifications->save($notification)) {
            $notifs = $this->Notifications->find('all')
                ->where(['readby IS NULL'])
                ->where(['user_id' => $this->request->getAttribute('identity')->id])
                ->count();
            $this->response = $this->response->withStringBody(json_encode(['success' => true, 'readby' => $notification->readby, 'nbUnread' => $notifs]));
        } else {
            $this->response = $this->response->withStringBody(json_encode(['success' => false]));
        }

        $this->response = $this->response->withType('json');
        return $this->response;
    }
}
