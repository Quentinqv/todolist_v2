<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Message;
use Authorization\IdentityInterface;

/**
 * Message policy
 */
class MessagePolicy
{
    public function canIndex(IdentityInterface $user, Message $message)
    {
        return true;
    }

    /**
     * Check if $user can add Message
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Message $message
     * @return bool
     */
    public function canAdd(IdentityInterface $user, Message $message)
    {
        return true;
    }

    /**
     * Check if $user can edit Message
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Message $message
     * @return bool
     */
    public function canEdit(IdentityInterface $user, Message $message)
    {
        return $this->isAuthor($user, $message);
    }

    /**
     * Check if $user can delete Message
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Message $message
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Message $message)
    {
        return $this->isAuthor($user, $message);
    }

    /**
     * Check if $user can view Message
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Message $message
     * @return bool
     */
    public function canView(IdentityInterface $user, Message $message)
    {
        return $this->isAuthor($user, $message);
    }

    protected function isAuthor($user = null, Message $message)
    {
        if ($user === null) {
            return false;
        }
        return $message->sender_id === $user->getIdentifier() || $message->receiver_id === $user->getIdentifier();
    }
}
