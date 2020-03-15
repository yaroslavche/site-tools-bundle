<?php
declare(strict_types=1);

namespace Yaroslavche\SiteToolsBundle\Service;

use Symfony\Component\Security\Core\User\UserInterface;
use Yaroslavche\SiteToolsBundle\Storage\StorageInterface;

/**
 * Class UserFriend
 * @package Yaroslavche\SiteToolsBundle\Service
 */
class UserFriend
{
    private StorageInterface $storage;

    /**
     * UserFriend constructor.
     * @param StorageInterface $storage
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param UserInterface $user
     * @param UserInterface $applicantUser
     */
    public function add(UserInterface $user, UserInterface $applicantUser): void
    {
        $this->storage->addFriend($user, $applicantUser);
    }

    /**
     * @param UserInterface $user
     * @param UserInterface $applicantUser
     */
    public function remove(UserInterface $user, UserInterface $applicantUser): void
    {
        $this->storage->removeFriend($user, $applicantUser);
    }

    /**
     * @param UserInterface $user
     * @return array<string>
     */
    public function get(UserInterface $user): array
    {
        return $this->storage->getFriends($user);
    }
}
