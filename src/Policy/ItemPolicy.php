<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Item;
use Authorization\IdentityInterface;

/**
 * Item policy
 */
class ItemPolicy
{
    /**
     * Check if $user can add Item
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Item $item
     * @return bool
     */
    public function canAdd(IdentityInterface $user, Item $item)
    {
        return $this->isAuthor($user, $item);
    }

    /**
     * Check if $user can edit Item
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Item $item
     * @return bool
     */
    public function canEdit(IdentityInterface $user, Item $item)
    {
        return $this->isAuthor($user, $item);
    }

    /**
     * Check if $user can delete Item
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Item $item
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Item $item)
    {
        return $this->isAuthor($user, $item);
    }

    public function canSave(IdentityInterface $user, Item $item)
    {
        return $this->isAuthor($user, $item);
    }

    protected function isAuthor($user = null, Item $item)
    {
        if ($user === null) {
            return false;
        }
        return $item->listing->user_id === $user->getIdentifier();
    }
}
