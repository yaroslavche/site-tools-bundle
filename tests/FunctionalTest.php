<?php
declare(strict_types=1);

namespace Yaroslavche\SiteToolsBundle\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Yaroslavche\SiteToolsBundle\Service\UserLike;
use Yaroslavche\SiteToolsBundle\Service\UserOnline;
use Yaroslavche\SiteToolsBundle\Service\UserProfileView;
use Yaroslavche\SiteToolsBundle\Service\UserRating;

/**
 * Class FunctionalTest
 * @package Yaroslavche\SiteToolsBundle\Tests
 */
class FunctionalTest extends TestCase
{
    private ContainerInterface $container;

    protected function setUp(): void
    {
        $kernel = new Kernel();
        $kernel->boot();
        $this->container = $kernel->getContainer();
    }

    public function testServiceWiring()
    {
        $userLikeService = $this->container->get('yaroslavche_site_tools.service.user_like');
        $this->assertInstanceOf(UserLike::class, $userLikeService);
        $userOnlineService = $this->container->get('yaroslavche_site_tools.service.user_online');
        $this->assertInstanceOf(UserOnline::class, $userOnlineService);
        $userProfileViewService = $this->container->get('yaroslavche_site_tools.service.user_profile_view');
        $this->assertInstanceOf(UserProfileView::class, $userProfileViewService);
        $userRatingService = $this->container->get('yaroslavche_site_tools.service.user_rating');
        $this->assertInstanceOf(UserRating::class, $userRatingService);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
