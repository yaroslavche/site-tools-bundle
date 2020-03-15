<?php
declare(strict_types=1);

namespace Yaroslavche\SiteToolsBundle\Service;

use DateTimeImmutable;
use Symfony\Component\Security\Core\User\UserInterface;
use Yaroslavche\SiteToolsBundle\Storage\StorageInterface;

/**
 * Class UserOnline
 * @package Yaroslavche\SiteToolsBundle\Service
 */
class UserOnline
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

    /** @param UserInterface $user */
    public function setOnline(UserInterface $user): void
    {
        $this->storage->setOnline($user);
    }

    /** @param UserInterface $user */
    public function setOffline(UserInterface $user): void
    {
        $this->storage->setOffline($user);
    }

    /**
     * @param string $username
     */
    public function setOfflineByUsername(string $username): void
    {
        $this->storage->setOfflineByUsername($username);
    }

    /** @return int */
    public function getOnlineCount(): int
    {
        return $this->storage->getOnlineCount();
    }

    /** @return array<string, DateTimeImmutable> $username => $active */
    public function getOnlineUsers(): array
    {
        return $this->storage->getOnlineUsers();
    }

    /**
     * @param UserInterface $user
     * @return bool
     */
    public function isOnline(UserInterface $user): bool
    {
        return $this->storage->isOnline($user);
    }
}
