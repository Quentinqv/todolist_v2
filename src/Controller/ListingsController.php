<?php

declare(strict_types=1);

namespace App\Controller;

use Seld\JsonLint\Undefined;

/**
 * Listings Controller
 *
 * @property \App\Model\Table\ListingsTable $Listings
 * @method \App\Model\Entity\Listing[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ListingsController extends AppController
{
    /**
     * All method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->Authorization->skipAuthorization();
        
        $this->paginate = [
            'contain' => ['Users', 'ParentListings', 'ChildListings'],
            'limit' => 5,
        ];
        // Get all non private listings
        $listings = $this->paginate($this->Listings->find('all')->where(['Listings.private' => false])->contain(['Users', 'ParentListings', 'ChildListings']), ['scope' => 'listings', 'limit' => 5]);

        $privateListings = [];
        if (!empty($this->request->getAttribute('identity'))) {
            // Get all private listings own by the current user
            $privateListings = $this->Listings->find('all')->where(['Listings.user_id' => $this->request->getAttribute('identity')->id])->contain(['Users', 'ParentListings', 'ChildListings']);
        }

        $this->set(compact('listings', 'privateListings'));
    }

    /**
     * View method
     *
     * @param string|null $id Listing id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        // Get all infos on list and childs with the id
        $listing = $this->Listings->find('all')
            ->where(['Listings.id' => $id])
            ->contain(['Users', 'ParentListings', 'ChildListings']);

        $listing = $listing->first();
        $this->Authorization->authorize($listing);

        $item = $this->fetchTable("Items")->newEmptyEntity();

        $this->set(compact('listing', 'item'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $listing = $this->Listings->newEmptyEntity();
        $this->Authorization->authorize($listing);
        if ($this->request->is('post')) {
            $listing = $this->Listings->patchEntity($listing, $this->request->getData());
            $listing->user_id = $this->request->getAttribute('identity')->id;

            if ($this->Listings->save($listing)) {
                $this->Flash->success(__('The listing has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The listing could not be saved. Please, try again.'));
        }
        $users = $this->Listings->Users->find('list', ['limit' => 200])->all();
        $parentListings = $this->Listings->ParentListings->find('list', ['limit' => 200])->all();
        $this->set(compact('listing', 'users', 'parentListings'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Listing id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $listing = $this->Listings->get($id, [
            'contain' => [],
        ]);
        $this->Authorization->authorize($listing);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $listing = $this->Listings->patchEntity($listing, $this->request->getData());
            if ($this->Listings->save($listing)) {
                $this->Flash->success(__('The listing has been saved.'));

                return $this->redirect(['action' => 'view', $listing->id]);
            }
            $this->Flash->error(__('The listing could not be saved. Please, try again.'));
        }
        $users = $this->Listings->Users->find('list', ['limit' => 200])->all();
        $parentListings = $this->Listings->ParentListings->find('list', ['limit' => 200])->all();
        $this->set(compact('listing', 'users', 'parentListings'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Listing id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $listing = $this->Listings->get($id);
        $this->Authorization->authorize($listing);
        if ($this->Listings->delete($listing)) {
            // Define all parent_id to null where parent_id is the deleted listing
            $this->fetchTable("Listings")->updateAll(['parent_id' => null], ['parent_id' => $id]);
            $this->Flash->success(__('The listing has been deleted.'));
        } else {
            $this->Flash->error(__('The listing could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Copy method
     * 
     * @param string|null $id Listing id.
     * @return \Cake\Http\Response|null|void Redirects on successful copy, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function copy($id = null)
    {
        if (empty($id)) {
            return $this->redirect(['action' => 'index']);
        }

        $list = $this->Listings->newEntity( $this->Listings->get($id,[
            'contain' => ['ChildListings']])->toArray() );
        // Authorization sur $list
        $this->Authorization->authorize($list);
        $list->setNew(true);
        $list->set(['user_id'=>$this->request->getAttribute('identity')->id]);
        $list->set(['parent_id'=>$id]);
        $list->set(['name'=>$list->name.' - Copy']);

        if ($this->Listings->save($list)) {
            $this->Flash->success(__('The listing has been copied and saved.'));
            return $this->redirect(['action' => 'index']);
        }
        $this->Flash->error(__('The listing could not be copied and saved. Please, try again.')); 
    }
}
