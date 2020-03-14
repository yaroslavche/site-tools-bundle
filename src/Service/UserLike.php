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
    public const KEY_FORMAT = '%s:%s';

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

    /**
     * @param UserInterface $voterUser
     * @param UserInterface $applicantUser
     */
    public function add(UserInterface $voterUser, UserInterface $applicantUser): void
    {
        $this->redis->sAdd(
            sprintf(static::KEY_FORMAT, $this->key, $applicantUser->getUsername()),
            $voterUser->getUsername()
        );
    }

    /**
     * @param UserInterface $voterUser
     * @param UserInterface $applicantUser
     */
    public function remove(UserInterface $voterUser, UserInterface $applicantUser): void
    {
        $this->redis->sRem(
            sprintf(static::KEY_FORMAT, $this->key, $applicantUser->getUsername()),
            $voterUser->getUsername()
        );
    }

    /**
     * @param UserInterface $user
     * @return array<string>
     */
    public function get(UserInterface $user): array
    {
        return $this->redis->sMembers(sprintf(static::KEY_FORMAT, $this->key, $user->getUsername()));
    }
}
