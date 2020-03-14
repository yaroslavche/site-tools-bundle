<?php
declare(strict_types=1);

namespace Yaroslavche\SiteToolsBundle\Service;

use Redis;
use Symfony\Component\Security\Core\User\UserInterface;
use Yaroslavche\SiteToolsBundle\Exception\InvalidRatingRange;

/**
 * Class UserRating
 * @package Yaroslavche\SiteToolsBundle\Service
 */
class UserRating
{
    public const KEY_FORMAT = '%s:%s';
    
    private Redis $redis;
    private string $key;
    private int $maxRating;

    /**
     * Rating constructor.
     * @param string $key
     */
    public function __construct(string $key, int $maxRating = 5)
    {
        $this->key = $key;
        $this->maxRating = $maxRating;
        $this->redis = new Redis();
        $this->redis->connect('localhost');
    }

    /**
     * @param UserInterface $voterUser
     * @param UserInterface $applicantUser
     * @param int $rating
     * @throws InvalidRatingRange
     */
    public function add(UserInterface $voterUser, UserInterface $applicantUser, int $rating): void
    {
        if ($rating > $this->maxRating || $rating < 0) {
            throw new InvalidRatingRange(sprintf('%d [0, %d]', $rating, $this->maxRating));
        }
        $key = sprintf(static::KEY_FORMAT, $this->key, $applicantUser->getUsername());
    }

    /**
     * @param UserInterface $voterUser
     * @param UserInterface $applicantUser
     */
    public function remove(UserInterface $voterUser, UserInterface $applicantUser): void
    {
    }

    /**
     * @param UserInterface $user
     * @return array<string>
     */
    public function get(UserInterface $user): array
    {
        return [];
    }

    /**
     * @param UserInterface $user
     * @return float
     */
    public function average(UserInterface $user): float
    {
        return .0;
    }
}
