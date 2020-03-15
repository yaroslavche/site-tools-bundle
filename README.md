```php
use Yaroslavche\SiteToolsBundle\Service\UserFriend;
use Yaroslavche\SiteToolsBundle\Service\UserLike;
use Yaroslavche\SiteToolsBundle\Service\UserOnline;
use Yaroslavche\SiteToolsBundle\Service\UserProfileView;
use Yaroslavche\SiteToolsBundle\Service\UserRating;

class UserService
{
    private UserFriend $userFriendService;
    private UserLike $userLikeService;
    private UserOnline $userOnlineService;
    private UserProfileView $userProfileViewService;
    private UserRating $userRatingService;

    /**
     * UserService constructor.
     * @param UserFriend $userFriendService
     * @param UserLike $userLikeService
     * @param UserOnline $userOnlineService
     * @param UserProfileView $userProfileViewService
     * @param UserRating $userRatingService
     */
    public function __construct(
        UserFriend $userFriendService,
        UserLike $userLikeService,
        UserOnline $userOnlineService,
        UserProfileView $userProfileViewService,
        UserRating $userRatingService
    ) {
        $this->userFriendService = $userFriendService;
        $this->userLikeService = $userLikeService;
        $this->userOnlineService = $userOnlineService;
        $this->userProfileViewService = $userProfileViewService;
        $this->userRatingService = $userRatingService;
    }
    
    public function methods(): void
    {
        $alice = new \Yaroslavche\SiteToolsBundle\Tests\Fixture\User('Alice');
        $bob = new \Yaroslavche\SiteToolsBundle\Tests\Fixture\User('Bob');
        
        # like
        /** @var array<string> $likes */
        $likes = $this->userLikeService->get($alice);
        $this->userLikeService->add($alice, $bob);
        $this->userLikeService->remove($alice, $bob);

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

        # rating (NOT IMPLEMENTED YET)
        /** @var float $rating */
        $rating = $this->userRatingService->getRating($alice);
        /** @var array<string, int> $ratings $username => $rating */
        $ratings = $this->userRatingService->getRatings($alice);
        $this->userRatingService->add($alice, $bob, 5);
        $this->userRatingService->remove($alice, $bob);
        
        # friend
        /** @var array<string> $friends */
        $friends = $this->userFriendService->get($alice);
        $this->userFriendService->add($alice, $bob);
        $this->userFriendService->remove($alice, $bob);
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

```yaml
# config/packages/yaroslavche_site_tools.yaml
yaroslavche_site_tools:
  host: 'localhost'
```
