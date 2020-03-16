<?php
declare(strict_types=1);

namespace Yaroslavche\SiteToolsBundle\Storage;

use DateTimeImmutable;
use Redis;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class RedisStorage
 * @package Yaroslavche\SiteToolsBundle\Storage
 */
class RedisStorage implements StorageInterface
{
    public const KEY_FORMAT = '%s:%s';
    public const HASH_KEY_ACTIVE = 'active';
    public const HASH_KEY_RATING = 'rating';
    private Redis $redis;

    /**
     * RedisStorage constructor.
     * @param array<string> $config
     */
    public function __construct(array $config = [])
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'host' => 'localhost',
            'port' => 6379,
            'timeout' => .0,
            'reserved' => null,
            'retryInterval' => 0,
            'readTimeout' => .0,
        ]);
        $resolver
            ->setAllowedTypes('host', ['null', 'string'])
            ->setAllowedTypes('port', ['null', 'int'])
            ->setAllowedTypes('timeout', ['null', 'float'])
            ->setAllowedTypes('reserved', ['null'])
            ->setAllowedTypes('retryInterval', ['null', 'int'])
            ->setAllowedTypes('readTimeout', ['null', 'float']);
        $config = $resolver->resolve($config);
        $this->redis = new Redis();
        $this->redis->connect(
            $config['host'],
            $config['port'],
            $config['timeout'],
            $config['reserved'],
            $config['retryInterval'],
            $config['readTimeout'],
            );
    }

    /** @inheritDoc */
    public function addLike(UserInterface $voterUser, UserInterface $applicantUser): void
    {
        $this->redis->sAdd(
            sprintf(static::KEY_FORMAT, 'user_like', $applicantUser->getUsername()),
            $voterUser->getUsername()
        );
    }

    /** @inheritDoc */
    public function removeLike(UserInterface $voterUser, UserInterface $applicantUser): void
    {
        $this->redis->sRem(
            sprintf(static::KEY_FORMAT, 'user_like', $applicantUser->getUsername()),
            $voterUser->getUsername()
        );
    }

    /** @inheritDoc */
    public function getLikes(UserInterface $user): array
    {
        return $this->redis->sMembers(sprintf(static::KEY_FORMAT, 'user_like', $user->getUsername()));
    }

    /** @inheritDoc */
    public function setOnline(UserInterface $user): void
    {
        $this->redis->sAdd('user_online', $user->getUsername());
        $this->redis->hMSet(sprintf(static::KEY_FORMAT, 'user_online', $user->getUsername()), [
            static::HASH_KEY_ACTIVE => time(),
        ]);
    }

    /** @inheritDoc */
    public function setOffline(UserInterface $user): void
    {
        $this->setOfflineByUsername($user->getUsername());
    }

    /** @inheritDoc */
    public function setOfflineByUsername(string $username): void
    {
        $this->redis->sRem('user_online', $username);
        $this->redis->hDel(sprintf(static::KEY_FORMAT, 'user_online', $username), ...[static::HASH_KEY_ACTIVE]);
    }

    /** @inheritDoc */
    public function getOnlineCount(): int
    {
        return count($this->redis->sMembers('user_online'));
    }

    /** @inheritDoc */
    public function getOnlineUsers(): array
    {
        $onlineUsers = array_flip($this->redis->sMembers('user_online'));
        foreach ($onlineUsers as $username => $i) {
            $activeTimestamp = $this->redis->hGet(sprintf(static::KEY_FORMAT, 'user_online', $username), static::HASH_KEY_ACTIVE);
            $onlineUsers[$username] = (new DateTimeImmutable())->setTimestamp((int)$activeTimestamp);
        }
        return $onlineUsers;
    }

    /** @inheritDoc */
    public function isOnline(UserInterface $user): bool
    {
        return $this->redis->sIsMember('user_online', $user->getUsername());
    }

    /** @inheritDoc */
    public function profileViewIncrement(UserInterface $user): void
    {
        $this->redis->hIncrBy('user_profile_view', $user->getUsername(), 1);
    }

    /** @inheritDoc */
    public function profileViewCount(UserInterface $user): int
    {
        return intval($this->redis->hGet('user_profile_view', $user->getUsername()));
    }

    /** @inheritDoc */
    public function addRating(UserInterface $voterUser, UserInterface $applicantUser, int $rating): void
    {
        $this->redis->sAdd(
            sprintf(static::KEY_FORMAT, 'user_ratings', $applicantUser->getUsername()),
            $voterUser->getUsername()
        );
        $this->redis->hMSet(sprintf(static::KEY_FORMAT, 'user_rating', $voterUser->getUsername()), [
            static::HASH_KEY_RATING => $rating,
        ]);
    }

    /** @inheritDoc */
    public function removeRating(UserInterface $voterUser, UserInterface $applicantUser): void
    {
        $this->redis->sRem(
            sprintf(static::KEY_FORMAT, 'user_ratings', $applicantUser->getUsername()),
            $voterUser->getUsername()
        );
        $this->redis->hDel(
            sprintf(static::KEY_FORMAT, 'user_rating', $applicantUser->getUsername()),
            ...[static::HASH_KEY_RATING]
        );
    }

    /** @inheritDoc */
    public function getRatings(UserInterface $user): array
    {
        $ratings = array_flip($this->redis->sMembers(
            sprintf(static::KEY_FORMAT, 'user_ratings', $user->getUsername())
        ));
        foreach ($ratings as $username => $i) {
            $rating = $this->redis->hGet(
                sprintf(static::KEY_FORMAT, 'user_rating', $username),
                static::HASH_KEY_RATING
            );
            $ratings[$username] = (int)$rating;
        }
        return $ratings;
    }

    /** @inheritDoc */
    public function getRating(UserInterface $user): float
    {
        $ratings = $this->getRatings($user);
        if (empty($ratings)) {
            return .0;
        }
        return array_sum($ratings) / count($ratings);
    }

    /** @inheritDoc */
    public function addFriend(UserInterface $user, UserInterface $applicantUser): void
    {
        $this->redis->sAdd(
            sprintf(static::KEY_FORMAT, 'user_friend', $applicantUser->getUsername()),
            $user->getUsername()
        );
    }

    /** @inheritDoc */
    public function removeFriend(UserInterface $user, UserInterface $applicantUser): void
    {
        $this->redis->sRem(
            sprintf(static::KEY_FORMAT, 'user_friend', $applicantUser->getUsername()),
            $user->getUsername()
        );
    }

    /** @inheritDoc */
    public function getFriends(UserInterface $user): array
    {
        return $this->redis->sMembers(sprintf(static::KEY_FORMAT, 'user_friend', $user->getUsername()));
    }

    public function isFriend(UserInterface $user, UserInterface $applicantUser): bool
    {
        return $this->redis->sIsMember(
            sprintf(static::KEY_FORMAT, 'user_friend', $user->getUsername()),
            $applicantUser->getUsername()
        );
    }
}
