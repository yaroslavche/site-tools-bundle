<?php
declare(strict_types=1);

namespace Yaroslavche\SiteToolsBundle\Service;

use DateTimeImmutable;
use Redis;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserOnline
 * @package Yaroslavche\SiteToolsBundle\Service
 */
class UserOnline
{
    public const KEY_FORMAT = '%s:%s';
    public const HASH_KEY_ACTIVE = 'active';
    
    private Redis $redis;
    private string $key;

    /**
     * Online constructor.
     * @param string $key
     */
    public function __construct(string $key)
    {
        $this->key = $key;
        $this->redis = new Redis();
        $this->redis->connect('localhost');
    }


    /** @param UserInterface $user */
    public function setOnline(UserInterface $user): void
    {
        $this->redis->sAdd($this->key, $user->getUsername());
        $this->redis->hMSet(sprintf(static::KEY_FORMAT, $this->key, $user->getUsername()), [
            static::HASH_KEY_ACTIVE => time(),
        ]);
    }

    /** @param string $username */
    public function setOffline(string $username): void
    {
        $this->redis->sRem($this->key, $username);
        $this->redis->hDel(sprintf(static::KEY_FORMAT, $this->key, $username), ...[static::HASH_KEY_ACTIVE]);
    }

    /** @return array<string, DateTimeImmutable> $username => $active */
    public function getOnlineUsers(): array
    {
        $onlineUsers = array_flip($this->redis->sMembers($this->key));
        foreach ($onlineUsers as $username => $i) {
            $activeTimestamp = $this->redis->hGet(sprintf(static::KEY_FORMAT, $this->key, $username), static::HASH_KEY_ACTIVE);
            $onlineUsers[$username] = (new DateTimeImmutable())->setTimestamp((int)$activeTimestamp);
        }
        return $onlineUsers;
    }

    /**
     * @param UserInterface $user
     * @return bool
     */
    public function isOnline(UserInterface $user): bool
    {
        return $this->redis->sIsMember($this->key, $user->getUsername());
    }
}
