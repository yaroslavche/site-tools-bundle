<?php
declare(strict_types=1);

namespace Yaroslavche\SiteToolsBundle\Service;

use Symfony\Component\Security\Core\User\UserInterface;
use Yaroslavche\SiteToolsBundle\Storage\StorageInterface;

/**
 * Class UserLike
 * @package Yaroslavche\SiteToolsBundle\Service
 */
class UserLike
{
    private StorageInterface $storage;

    /**
     * Rating constructor.
     * @param StorageInterface $storage
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param UserInterface $voterUser
     * @param UserInterface $applicantUser
     */
    public function add(UserInterface $voterUser, UserInterface $applicantUser): void
    {
        $this->storage->addLike($voterUser, $applicantUser);
    }

    /**
     * @param UserInterface $voterUser
     * @param UserInterface $applicantUser
     */
    public function remove(UserInterface $voterUser, UserInterface $applicantUser): void
    {
        $this->storage->removeLike($voterUser, $applicantUser);
    }

    /**
     * @param UserInterface $user
     * @return array<string>
     */
    public function get(UserInterface $user): array
    {
        return $this->storage->getLikes($user);
    }
}
