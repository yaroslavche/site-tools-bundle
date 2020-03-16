[![Build Status](https://travis-ci.org/yaroslavche/site-tools-bundle.svg?branch=master)](https://travis-ci.org/yaroslavche/site-tools-bundle)
[![codecov](https://codecov.io/gh/yaroslavche/site-tools-bundle/branch/master/graph/badge.svg)](https://codecov.io/gh/yaroslavche/site-tools-bundle)
[![Infection MSI](https://badge.stryker-mutator.io/github.com/yaroslavche/site-tools-bundle/master)](https://infection.github.io)

```php
use Yaroslavche\SiteToolsBundle\Service\UserFriend;
use Yaroslavche\SiteToolsBundle\Service\UserLike;
use Yaroslavche\SiteToolsBundle\Service\UserOnline;
use Yaroslavche\SiteToolsBundle\Service\UserProfileView;
use Yaroslavche\SiteToolsBundle\Service\UserRating;
use Yaroslavche\SiteToolsBundle\Service\UserVote;

class UserService
{
    private UserFriend $userFriendService;
    private UserLike $userLikeService;
    private UserOnline $userOnlineService;
    private UserProfileView $userProfileViewService;
    private UserRating $userRatingService;
    private UserVote $userVoteService;

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
        $this->userFriendService = $userFriendService;
        $this->userLikeService = $userLikeService;
        $this->userOnlineService = $userOnlineService;
        $this->userProfileViewService = $userProfileViewService;
        $this->userRatingService = $userRatingService;
        $this->userVoteService = $userVoteService;
    }
    
    public function methods(): void
    {
        $alice = new \Yaroslavche\SiteToolsBundle\Tests\Fixture\User('Alice');
        $bob = new \Yaroslavche\SiteToolsBundle\Tests\Fixture\User('Bob');
        
                
        # friend
        /** @var array<string> $friends */
        $friends = $this->userFriendService->get($alice);
        $this->userFriendService->add($alice, $bob);
        $this->userFriendService->remove($alice, $bob);
        /** @var bool $isFriend */
        $isFriend = $this->userFriendService->isFriend($alice, $bob);

        # like
        /** @var array<string> $likes */
        $likes = $this->userLikeService->get($alice);
        $this->userLikeService->like($alice, $bob);
        $this->userLikeService->unlike($alice, $bob);

        # online
        /** @var int $count */
        $count = $this->userOnlineService->getOnlineCount();
        /** @var array<string, DateTimeImmutable> $users $username => $active */
        $users = $this->userOnlineService->getOnlineUsers();
        $this->userOnlineService->setOnline($alice);
        $this->userOnlineService->setOffline($alice);
        $this->userOnlineService->setOfflineByUsername($alice->getUsername());
        /** @var bool $isOnline */
        $isOnline = $this->userOnlineService->isOnline($alice);

        # profile view
        $this->userProfileViewService->increment($alice);
        /** @var int $count */
        $count = $this->userProfileViewService->count($alice);

        # rating
        /** @var float $rating */
        $rating = $this->userRatingService->getRating($alice);
        /** @var array<string, int> $ratings $username => $rating */
        $ratings = $this->userRatingService->getRatings($alice);
        $this->userRatingService->add($alice, $bob, 5);
        $this->userRatingService->remove($alice, $bob);
        
        # vote (NOT IMPLEMENTED YET)
        $this->userVoteService->up($alice, $bob);
        $this->userVoteService->down($alice, $bob);
        $this->userVoteService->remove($alice, $bob);
        /** @var array<string> $votes */
        $votes = $this->userVoteService->get($bob);
        /** @var int $voteValue */
        $voteValue = $this->userVoteService->getValue($bob);
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
