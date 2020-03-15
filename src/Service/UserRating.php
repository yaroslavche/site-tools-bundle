<?php
declare(strict_types=1);

namespace Yaroslavche\SiteToolsBundle\Service;

use Redis;
use Symfony\Component\Security\Core\User\UserInterface;
use Yaroslavche\SiteToolsBundle\Exception\InvalidRatingRangeException;
use Yaroslavche\SiteToolsBundle\Storage\StorageInterface;

/**
 * Class UserRating
 * @package Yaroslavche\SiteToolsBundle\Service
 */
class UserRating
{
    private StorageInterface $storage;
    private int $maxRating;

    /**
     * UserRating constructor.
     * @param StorageInterface $storage
     * @param int $maxRating
     */
    public function __construct(StorageInterface $storage, int $maxRating = 5)
    {
        $this->storage = $storage;
        $this->maxRating = $maxRating;
    }

    /**
     * @param UserInterface $voterUser
     * @param UserInterface $applicantUser
     * @param int $rating
     * @throws InvalidRatingRangeException
     */
    public function add(UserInterface $voterUser, UserInterface $applicantUser, int $rating): void
    {
        if ($rating > $this->maxRating || $rating < 0) {
            throw new InvalidRatingRangeException(sprintf('%d [0, %d]', $rating, $this->maxRating));
        }
        $this->storage->addRating($voterUser, $applicantUser, $rating);
    }

    /**
     * @param UserInterface $voterUser
     * @param UserInterface $applicantUser
     */
    public function remove(UserInterface $voterUser, UserInterface $applicantUser): void
    {
        $this->storage->removeRating($voterUser, $applicantUser);
    }

    /**
     * @param UserInterface $user
     * @return array<string>
     */
    public function getRatings(UserInterface $user): array
    {
        return $this->storage->getRatings($user);
    }

    /**
     * @param UserInterface $user
     * @return float
     */
    public function getRating(UserInterface $user): float
    {
        return $this->storage->getRating($user);
    }
}
