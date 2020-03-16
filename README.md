[![Build Status](https://travis-ci.org/yaroslavche/site-tools-bundle.svg?branch=master)](https://travis-ci.org/yaroslavche/site-tools-bundle)
[![codecov](https://codecov.io/gh/yaroslavche/site-tools-bundle/branch/master/graph/badge.svg)](https://codecov.io/gh/yaroslavche/site-tools-bundle)
[![Infection MSI](https://badge.stryker-mutator.io/github.com/yaroslavche/site-tools-bundle/master)](https://infection.github.io)

```php
<?php

use Symfony\Component\Security\Core\User\UserInterface;
use Yaroslavche\SiteToolsBundle\Service\UserFriend;
use Yaroslavche\SiteToolsBundle\Service\UserLike;
use Yaroslavche\SiteToolsBundle\Service\UserOnline;
use Yaroslavche\SiteToolsBundle\Service\UserProfileView;
use Yaroslavche\SiteToolsBundle\Service\UserRating;
use Yaroslavche\SiteToolsBundle\Service\UserVote;

class UserService
{
    /**
     * UserService constructor.
     * @param UserFriend $userFriendService
     * @param UserLike $userLikeService
     * @param UserOnline $userOnlineService
     * @param UserProfileView $userProfileViewService
     * @param UserRating $userRatingService
     * @param UserVote $userVoteService
     */
    public function __construct(
        UserFriend $userFriendService,
        UserLike $userLikeService,
        UserOnline $userOnlineService,
        UserProfileView $userProfileViewService,
        UserRating $userRatingService,
        UserVote $userVoteService
    ) {
        /** @var UserInterface $alice, $bob */
        $alice = new \Yaroslavche\SiteToolsBundle\Tests\Fixture\User('Alice');
        $bob = new \Yaroslavche\SiteToolsBundle\Tests\Fixture\User('Bob');

        # -------------------- friend --------------------
        /** @var array<string> $friends */
        $friends = $userFriendService->get($alice);
        $userFriendService->add($alice, $bob);
        $userFriendService->remove($alice, $bob);
        /** @var bool $isFriend */
        $isFriend = $userFriendService->isFriend($alice, $bob);

        # -------------------- like --------------------
        /** @var array<string> $likes */
        $likes = $userLikeService->get($alice);
        $userLikeService->like($alice, $bob);
        $userLikeService->unlike($alice, $bob);

        # -------------------- online --------------------
        /** @var int $count */
        $count = $userOnlineService->getOnlineCount();
        /** @var array<string, DateTimeImmutable> $users $username => $active */
        $users = $userOnlineService->getOnlineUsers();
        $userOnlineService->setOnline($alice);
        $userOnlineService->setOffline($alice);
        $userOnlineService->setOfflineByUsername($alice->getUsername());
        /** @var bool $isOnline */
        $isOnline = $userOnlineService->isOnline($alice);
        
        # -------------------- profile view --------------------
        $userProfileViewService->increment($alice);
        /** @var int $count */
        $count = $userProfileViewService->count($alice);

        # -------------------- rating --------------------
        /** @var float $rating */
        $rating = $userRatingService->getRating($alice);
        /** @var array<string, int> $ratings $username => $rating */
        $ratings = $userRatingService->getRatings($alice);
        $userRatingService->add($alice, $bob, 5);
        $userRatingService->remove($alice, $bob);

        # -------------------- vote (NOT IMPLEMENTED YET) --------------------
        $userVoteService->up($alice, $bob);
        $userVoteService->down($alice, $bob);
        $userVoteService->remove($alice, $bob);
        /** @var array<string> $votes */
        $votes = $userVoteService->get($bob);
        /** @var int $voteValue */
        $voteValue = $userVoteService->getValue($bob);
    }
}
```

Now have just one storage interface implementation: Redis. And requires it. 

## Installation

```shell script
composer require yarolsavche/site-tools-bundle
```

```php
# config/bundles.php
return [
    # ...
    Yaroslavche\SiteToolsBundle\YaroslavcheSiteToolsBundle::class => ['all' => true],
];
```

Config not implemented yet, but requires at least `host` key:
```yaml
# config/packages/yaroslavche_site_tools.yaml
yaroslavche_site_tools:
  host: 'localhost'
```

## Using without Symfony project
```php
use Yaroslavche\SiteToolsBundle\Service\UserOnline;
use Yaroslavche\SiteToolsBundle\Storage\RedisStorage;

$storage = new RedisStorage(['host' => 'localhost']);
$userOnline = new UserOnline($storage);
$users = $userOnline->getOnlineUsers();
```

## Dev

```shell script
$ composer phpcs
$ composer phpstan
$ composer phpunit
$ composer coverage
$ composer infection
```
