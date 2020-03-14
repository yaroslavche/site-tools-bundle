<?php
declare(strict_types=1);

namespace Yaroslavche\SiteToolsBundle\Service;

use Redis;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserProfileView
 * @package Yaroslavche\SiteToolsBundle\Service
 */
class UserProfileView
{
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
    public function increment(UserInterface $user): void
    {
        $this->redis->hIncrBy($this->key, $user->getUsername(), 1);
    }

    /**
     * @param UserInterface $user
     * @return int
     */
    public function count(UserInterface $user): int
    {
        return intval($this->redis->hGet($this->key, $user->getUsername()));
    }
}
