<?php
declare(strict_types=1);

namespace Yaroslavche\SiteToolsBundle\Tests\Service;

use PHPUnit\Framework\TestCase;
use Yaroslavche\SiteToolsBundle\Service\UserProfileView;
use Yaroslavche\SiteToolsBundle\Storage\RedisStorage;
use Yaroslavche\SiteToolsBundle\Tests\Fixture\User;

/**
 * Class UserLikeTest
 * @package Yaroslavche\SiteToolsBundle\Tests\Service
 */
class UserProfileViewTest extends TestCase
{
    private UserProfileView $userProfileView;

    protected function setUp(): void
    {
        parent::setUp();
        $this->constructor();
    }

    private function constructor(): void
    {
        $storage = new RedisStorage(['host' => 'localhost']);
        $this->userProfileView = new UserProfileView($storage);
        $this->assertInstanceOf(UserProfileView::class, $this->userProfileView);
    }

    public function testProfileView()
    {
        $user = new User((string)mt_rand());
        $this->assertSame(0, $this->userProfileView->count($user));
        $this->userProfileView->increment($user);
        $this->assertSame(1, $this->userProfileView->count($user));
    }

    protected function tearDown(): void
    {
        # cleanup
    }
}
