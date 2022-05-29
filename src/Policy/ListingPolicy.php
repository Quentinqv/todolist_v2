<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Listing;
use Authorization\IdentityInterface;

/**
 * Listing policy
 */
class ListingPolicy
{
    /**
     * Check if $user can add Listing
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Listing $listing
     * @return bool
     */
    public function canAdd(IdentityInterface $user, Listing $listing)
    {
        return true;
    }

    /**
     * Check if $user can edit Listing
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Listing $listing
     * @return bool
     */
    public function canEdit(IdentityInterface $user, Listing $listing)
    {
        return $this->isAuthor($user, $listing);
    }

    /**
     * Check if $user can delete Listing
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Listing $listing
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Listing $listing)
    {
        return $this->isAuthor($user, $listing);
    }

    /**
     * Check if $user can view Listing
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Listing $listing
     * @return bool
     */
    public function canView($user = null, Listing $listing)
    {
        if ($listing->private) {
            return $this->isAuthor($user, $listing);
        } else {
            return true;
        }
    }

    public function canCopy(IdentityInterface $user, Listing $listing)
    {
        if ($listing->private) {
            $isAuthor = $this->isAuthor($user, $listing);
            if ($isAuthor) {
                return true;
            } else {
                $this->Flash->error(__('You cannot copy a private listing.'));
                return false;
            }
        } else {
            return true;
        }
    }

    protected function isAuthor($user = null, Listing $listing)
    {
        if ($user === null) {
            return false;
        }
        return $listing->user_id === $user->getIdentifier();
    }
}
