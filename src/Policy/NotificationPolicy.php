<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Notification;
use Authorization\IdentityInterface;

/**
 * Notification policy
 */
class NotificationPolicy
{
    /**
     * Check if $user can add Notification
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Notification $notification
     * @return bool
     */
    public function canAdd(IdentityInterface $user, Notification $notification)
    {
        return $this->isAuthor($user, $notification);
    }

    /**
     * Check if $user can delete Notification
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Notification $notification
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Notification $notification)
    {
        return $this->isAuthor($user, $notification);
    }

    public function canSeen(IdentityInterface $user, Notification $notification)
    {
        return $this->isAuthor($user, $notification);
    }

    protected function isAuthor($user = null, Notification $notification)
    {
        if ($user === null) {
            return false;
        }
        return $notification->user_id === $user->getIdentifier();
    }
}
