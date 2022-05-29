<?php
declare(strict_types=1);

namespace App\Controller;

class StatsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void
     */
    public function index()
    {
        // skip Authorization
        $this->Authorization->skipAuthorization();

        // Top 5 users that own the most listings from Listings table
        $topFiveUsersMostListings = $this->fetchTable('Listings')->find('all')
            ->contain(['Users'])
            ->select(['user_id', 'Users.pseudo', 'count_list' => 'COUNT(*)'])
            ->group(['user_id'])
            ->order(['count_list' => 'DESC'])
            ->limit(5);

        // Top 5 listings most copied from Listings table
        $topFiveListings = $this->fetchTable('Listings')->find('all')
            ->contain(['ParentListings'])
            ->select(['Listings.parent_id', 'ParentListings.name', 'count_copy' => 'COUNT(*)'])
            ->where(['Listings.private' => false])
            ->where(['Listings.parent_id IS NOT NULL'])
            ->group(['Listings.parent_id'])
            ->order(['count_copy' => 'DESC'])
            ->limit(5);

        // Top 5 Users that copied the most
        $topFiveUsersCopyTheMost = $this->fetchTable('Listings')->find('all')
            ->contain(['Users'])
            ->select(['user_id', 'Users.pseudo', 'count_copy' => 'COUNT(*)'])
            ->where(['Listings.parent_id IS NOT NULL'])
            ->group(['user_id'])
            ->order(['count_copy' => 'DESC'])
            ->limit(5);

        $this->set(compact('topFiveUsersMostListings', 'topFiveListings', 'topFiveUsersCopyTheMost'));
    }
}
