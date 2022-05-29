<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * NotificationsFixture
 */
class NotificationsFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'created' => '2022-05-24 17:08:26',
                'modified' => '2022-05-24 17:08:26',
                'content' => 'Lorem ipsum dolor sit amet',
                'user_id' => 1,
                'readby' => '2022-05-24 17:08:26',
            ],
        ];
        parent::init();
    }
}
