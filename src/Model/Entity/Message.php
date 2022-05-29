<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Message Entity
 *
 * @property int $id
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property string $subject
 * @property string $content
 * @property int $sender_id
 * @property int $receiver_id
 * @property \Cake\I18n\FrozenTime|null $readby
 *
 */
class Message extends Entity
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
        'subject' => true,
        'content' => true,
        'sender_id' => true,
        'receiver_id' => true,
        'readby' => true,
    ];

    // Virtual fields return id there is unread messages for the user
    protected function _getNbUnreadMessages()
    {
        return $this->_properties['readby'] === null;
    }
}
