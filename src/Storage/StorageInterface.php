<?php
declare(strict_types=1);

namespace Yaroslavche\SiteToolsBundle\Storage;

use DateTimeImmutable;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Interface StorageInterface
 * @package Yaroslavche\SiteToolsBundle\Storage
 */
interface StorageInterface
{
    /** Like */

    /**
     * @param UserInterface $voterUser
     * @param UserInterface $applicantUser
     */
    public function addLike(UserInterface $voterUser, UserInterface $applicantUser): void;

    /**
     * @param UserInterface $voterUser
     * @param UserInterface $applicantUser
     */
    public function removeLike(UserInterface $voterUser, UserInterface $applicantUser): void;

    /**
     * @param UserInterface $user
     * @return array<string> $usernames
     */
    public function getLikes(UserInterface $user): array;

    /** Online */

    /**
     * @param UserInterface $user
     */
    public function setOnline(UserInterface $user): void;

    /**
     * @param UserInterface $user
     */
    public function setOffline(UserInterface $user): void;

    /**
     * Try to avoid using this method and use setOffline instead.
     * Only when you really can't or wont pass UserInterface object.
     * Can be removed.
     *
     * @param string $user
     */
    public function setOfflineByUsername(string $user): void;

    /**
     * @return int
     */
    public function getOnlineCount(): int;

    /**
     * @return array<string, DateTimeImmutable> $username => $active
     */
    public function getOnlineUsers(): array;

    /**
     * @param UserInterface $user
     * @return bool
     */
    public function isOnline(UserInterface $user): bool;

    /** Profile View */

    /**
     * @param UserInterface $user
     */
    public function profileViewIncrement(UserInterface $user): void;

    /**
     * @param UserInterface $user
     * @return int
     */
    public function profileViewCount(UserInterface $user): int;

    /** Rating */

    /**
     * @param UserInterface $voterUser
     * @param UserInterface $applicantUser
     * @param int $rating
     */
    public function addRating(UserInterface $voterUser, UserInterface $applicantUser, int $rating): void;

    /**
     * @param UserInterface $voterUser
     * @param UserInterface $applicantUser
     */
    public function removeRating(UserInterface $voterUser, UserInterface $applicantUser): void;

    /**
     * @param UserInterface $user
     * @return array<string>
     */
    public function getRatings(UserInterface $user): array;

    /**
     * @param UserInterface $user
     * @return float
     */
    public function getRating(UserInterface $user): float;

    /**
     * @param UserInterface $user
     * @param UserInterface $applicantUser
     */
    public function addFriend(UserInterface $user, UserInterface $applicantUser): void;

    /**
     * @param UserInterface $user
     * @param UserInterface $applicantUser
     */
    public function removeFriend(UserInterface $user, UserInterface $applicantUser): void;

    /**
     * @param UserInterface $user
     * @return array<string> $usernames
     */
    public function getFriends(UserInterface $user): array;
}
