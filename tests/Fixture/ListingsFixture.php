<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ListingsFixture
 */
class ListingsFixture extends TestFixture
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
                'created' => '2022-05-24 17:08:35',
                'modified' => '2022-05-24 17:08:35',
                'name' => 'Lorem ipsum dolor sit amet',
                'private' => 1,
                'user_id' => 1,
                'style' => 'Lorem ipsum dolor ',
                'parent_id' => 1,
            ],
        ];
        parent::init();
    }
}
