```php
use DateTimeImmutable;
use Symfony\Component\Security\Core\User\UserInterface;
use Yaroslavche\SiteToolsBundle\Service\UserLike;
use Yaroslavche\SiteToolsBundle\Service\UserOnline;
use Yaroslavche\SiteToolsBundle\Service\UserProfileView;
use Yaroslavche\SiteToolsBundle\Service\UserRating;

class UserService
{
    private Security $security;
    private UserOnline $userOnlineService;
    private UserProfileView $userProfileViewService;
    private UserLike $userLikeService;
    private UserRating $userRatingService;

    /**
     * UserService constructor.
     * @param Security $security
     * @param UserLike $userLikeService
     * @param UserOnline $userOnlineService
     * @param UserProfileView $userProfileViewService
     * @param UserRating $userRatingService
     */
    public function __construct(
        Security $security,
        UserLike $userLikeService,
        UserOnline $userOnlineService,
        UserProfileView $userProfileViewService,
        UserRating $userRatingService
    ) {
        $this->security = $security;
        $this->userLikeService = $userLikeService;
        $this->userOnlineService = $userOnlineService;
        $this->userProfileViewService = $userProfileViewService;
        $this->userRatingService = $userRatingService;
    }
    
    public function methods(): void
    {
        $user1 = new \Yaroslavche\SiteToolsBundle\Tests\Fixture\User('Alice');
        $user2 = new \Yaroslavche\SiteToolsBundle\Tests\Fixture\User('Bob');
        
        ## like
        /** @var array<string> $likes */
        $likes = $this->userLikeService->get($user1);
        $this->userLikeService->add($user1, $user2);
        $this->userLikeService->remove($user1, $user2);

        # online
        /** @var int $count */
        $count = $this->userOnlineService->getOnlineCount();
        /** @var array<string, DateTimeImmutable> $users $username => $active */
        $users = $this->userOnlineService->getOnlineUsers();
        $this->userOnlineService->setOnline($user1);
        $this->userOnlineService->setOffline($user1);
        $this->userOnlineService->setOfflineByUsername($user1->getUsername());
        /** @var bool $isOnline */
        $isOnline = $this->userOnlineService->isOnline($user1);

        # profile view
        $this->userProfileViewService->increment($user1);
        $this->userProfileViewService->count($user1);

        # rating (NOT IMPLEMENTED YET)
        /** @var float $rating */
        $rating = $this->userRatingService->getRating($user1);
        /** @var array<string, int> $ratings $username => $rating */
        $ratings = $this->userRatingService->getRatings($user1);
        $this->userRatingService->add($user1, $user2, 5);
        $this->userRatingService->remove($user1, $user2);
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
