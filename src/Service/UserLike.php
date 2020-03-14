<?php
declare(strict_types=1);

namespace Yaroslavche\SiteToolsBundle\Service;

use Redis;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserLike
 * @package Yaroslavche\SiteToolsBundle\Service
 */
class UserLike
{
    private Redis $redis;
    private string $key;

    /**
     * Rating constructor.
     * @param string $key
     */
    public function __construct(string $key)
    {
        $this->key = $key;
        $this->redis = new Redis();
        $this->redis->connect('localhost');
    }

    public function add(UserInterface $voterUser, UserInterface $applicantUser): void
    {
    }

    public function remove(UserInterface $voterUser, UserInterface $applicantUser): void
    {
    }
}
