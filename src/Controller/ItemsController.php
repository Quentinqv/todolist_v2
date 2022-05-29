<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

/**
 * Items Controller
 *
 * @property \App\Model\Table\ItemsTable $Items
 * @method \App\Model\Entity\Item[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ItemsController extends AppController
{
    function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->loadComponent('RequestHandler');
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add($id = null)
    {
        $item = $this->Items->newEmptyEntity();
        if ($this->request->is('post')) {
            $item = $this->Items->patchEntity($item, $this->request->getData());
            $item->completed = false;
            $item->list_id = $id;
            $listing = $this->fetchTable("Listings")->get($id);
            $item->listing = $listing;
            $this->Authorization->authorize($item);
            if ($this->Items->save($item)) {
                $this->Flash->success(__('The item has been saved.'));

                return $this->redirect(['controller' => 'Listings', 'action' => 'view', $id]);
            } else {
                $this->Flash->error(__('The item could not be saved. Please, try again.'));
            }
        }
        $lists = $this->Items->Listings->find('list', ['limit' => 200])->all();
        $this->set(compact('item', 'lists'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Item id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $item = $this->Items->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $item = $this->Items->patchEntity($item, $this->request->getData());
            $this->Authorization->authorize($item);
            if ($this->Items->save($item)) {
                $this->Flash->success(__('The item has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The item could not be saved. Please, try again.'));
        }
        $lists = $this->Items->Listings->find('list', ['limit' => 200])->all();
        $this->set(compact('item', 'lists'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Item id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete', 'get']);
        $item = $this->Items->get($id);
        $this->Authorization->authorize($item);
        if ($this->Items->delete($item)) {
            $this->Flash->success(__('The item has been deleted.'));
        } else {
            $this->Flash->error(__('The item could not be deleted. Please, try again.'));
        }

        return $this->redirect(['controller' => 'Listings', 'action' => 'view', $id]);
    }

    public function save()
    {
        // Save le nouvel état de l'item
        $item = $this->Items->newEmptyEntity();
        $item = $this->Items->patchEntity($item, $this->request->getData());
        $item = $this->Items->get($this->request->getData('id'), [
            'contain' => ['Listings'],
        ]);
        $this->Authorization->authorize($item);

        if ($this->request->getData('completed') !== null) {
            $item->completed = $this->request->getData('completed');
            
            // Save
            if ($this->Items->save($item)) {
                $list = $this->fetchTable("Listings")->find('all')
                    ->where(['Listings.id' => $item->list_id])
                    ->contain(['ChildListings'])
                    ->first();
                    
                $this->response = $this->response->withStringBody(json_encode(['success' => true, 'advancement' => $list->get("percentage")]));
            } else {
                $this->response = $this->response->withStringBody(json_encode(['success' => false]));
            }
        }

        if ($this->request->getData('element') !== null && $this->request->getData('deadline') !== null) {
            $item->element = $this->request->getData('element');
            $item->deadline = $this->request->getData('deadline');
            
            // Save
            if ($this->Items->save($item)) {
                $this->response = $this->response->withStringBody(json_encode(['success' => true, 'deadline' => $item->get("deadlineFr"), 'outdated' => strtotime($item->deadline) < time()]));
            } else {
                $this->response = $this->response->withStringBody(json_encode(['success' => false]));
            }
        }

        $this->response = $this->response->withType('json');
        return $this->response;
    }
}
