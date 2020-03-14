<?php
declare(strict_types=1);

namespace Yaroslavche\SiteToolsBundle\Tests\Service;

use PHPUnit\Framework\TestCase;
use Yaroslavche\SiteToolsBundle\Service\UserProfileView;
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
        $this->userProfileView = new UserProfileView('test_user_profile_view');
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
