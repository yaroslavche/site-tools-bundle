<?php
declare(strict_types=1);

namespace Yaroslavche\SiteToolsBundle\Service;

use Redis;
use Symfony\Component\Security\Core\User\UserInterface;
use Yaroslavche\SiteToolsBundle\Storage\StorageInterface;

/**
 * Class UserProfileView
 * @package Yaroslavche\SiteToolsBundle\Service
 */
class UserProfileView
{
    private StorageInterface $storage;

    /**
     * UserProfileView constructor.
     * @param StorageInterface $storage
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /** @param UserInterface $user */
    public function increment(UserInterface $user): void
    {
        $this->storage->profileViewIncrement($user);
    }

    /**
     * @param UserInterface $user
     * @return int
     */
    public function count(UserInterface $user): int
    {
        return $this->storage->profileViewCount($user);
    }
}
