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
        $this->request->allowMethod(['post', 'delete']);
        // Récupère l'id de la liste à copier
        $listing = $this->Listings->get($id);
        $this->Authorization->authorize($listing);
        $newListing = $this->Listings->newEmptyEntity();
        $newListing->name = $listing->name . ' - Copy';
        $newListing->private = $listing->private;
        $newListing->user_id = $this->request->getAttribute('identity')->id;
        $newListing->style = $listing->style;
        $newListing->parent_id = $listing->id;

        // On récupère tous les items de la liste à copier
        $items = $this->fetchTable('Items')->find('all')->where(['list_id' => $id]);

        // On enregistre la liste
        if ($this->Listings->save($newListing)) {

            // On défini les id sur les items à copier
            foreach ($items as $key => $value) {
                $newItem = $this->fetchTable("Items")->newEmptyEntity();
                $newItem->element = $value->element;
                $newItem->completed = false;
                $newItem->list_id = $newListing->id;
                $newItem->deadline = $value->deadline;

                if (!$this->fetchTable("Items")->save($newItem)) {
                    $this->Flash->error(__('An item has not been copied. Please, try again.'));
                    $error = true;
                }
            }

            if (!isset($error)) {
                $this->Flash->success(__('The listing has been copied.'));

                // Send notifications to $listing->user_id
                $notification = $this->fetchTable('Notifications')->newEmptyEntity();
                $notification->user_id = $listing->user_id;
                $notification->content = 'Your list "' . $listing->name . '" has been copied.';
                $this->fetchTable('Notifications')->save($notification);

                return $this->redirect(['action' => 'view', $newListing->id]);
            }
        } else {
            $this->Flash->error(__('The listing could not be saved. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
