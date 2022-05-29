<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Listing Entity
 *
 * @property int $id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property string $name
 * @property bool $private
 * @property int $user_id
 * @property string $style
 * @property int|null $parent_id
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\ParentListing $parent_listing
 * @property \App\Model\Entity\ChildListing[] $child_listings
 */
class Listing extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'created' => true,
        'modified' => true,
        'name' => true,
        'private' => true,
        'user_id' => true,
        'style' => true,
        'parent_id' => true,
        'user' => true,
        'parent_listing' => true,
        'child_listings' => true,
    ];

    // Virtual field compute the percentage of completed tasks
    protected function _getPercentage()
    {
        $completed = 0;
        $total = 0;
        foreach ($this->child_listings as $child) {
            if ($child->completed) {
                $completed++;
            }
            $total++;
        }
        return $total > 0 ? round($completed / $total * 100) : 0;
    }

    // Virtual field compute the number of tasks
    protected function _getNbTasks()
    {
        if (gettype($this->child_listings) == 'array') {
            return count($this->child_listings);
        } else {
            return 0;
        }
    }
}
