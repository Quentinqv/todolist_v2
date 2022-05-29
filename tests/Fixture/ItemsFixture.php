<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ItemsFixture
 */
class ItemsFixture extends TestFixture
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
                'created' => '2022-05-24 17:08:38',
                'modified' => '2022-05-24 17:08:38',
                'element' => 'Lorem ipsum dolor sit amet',
                'completed' => 1,
                'list_id' => 1,
                'deadline' => '2022-05-24 17:08:38',
            ],
        ];
        parent::init();
    }
}
