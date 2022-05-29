<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Item Entity
 *
 * @property int $id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property string $element
 * @property bool $completed
 * @property int $list_id
 * @property \Cake\I18n\FrozenTime|null $deadline
 *
 * @property \App\Model\Entity\List $list
 */
class Item extends Entity
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
        'element' => true,
        'completed' => true,
        'list_id' => true,
        'deadline' => true,
        'list' => true,
    ];

    // Virtual field transform deadline to french date format
    protected function _getDeadlineFr()
    {
        if (gettype($this->deadline) == "string") {
            return $this->deadline ? date('d/m/Y, H:i:s', strtotime($this->deadline)) : null;
        } else {
            return $this->deadline ? $this->deadline->format('d/m/Y, H:i:s') : null;
        }
    }

    // Virtual field transform deadline from yyyy-mm-dd hh:mm:ss to yyyy-MM-dd hh:mm format
    protected function _getDeadlineInput()
    {
        // return $this->deadline ? $this->deadline->format('Y-m-d\TH:i') : null;

        if (gettype($this->deadline) == "string") {
            return $this->deadline ? date('Y-m-d\TH:i', strtotime($this->deadline)) : null;
        } else {
            return $this->deadline ? $this->deadline->format('Y-m-d\TH:i') : null;
        }
    }
}
