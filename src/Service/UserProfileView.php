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

    public function incrementCounter(UserInterface $user)
    {
        $this->redis->hIncrBy($this->key, $user->getUsername(), 1);
    }

    public function getCounter(UserInterface $user): int
    {
        return intval($this->redis->hGet($this->key, $user->getUsername()));
    }
}
