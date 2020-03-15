<?php
declare(strict_types=1);

namespace Yaroslavche\SiteToolsBundle\Tests\Service;

use PHPUnit\Framework\TestCase;
use Yaroslavche\SiteToolsBundle\Service\UserOnline;
use Yaroslavche\SiteToolsBundle\Storage\RedisStorage;
use Yaroslavche\SiteToolsBundle\Tests\Fixture\User;

/**
 * Class UserLikeTest
 * @package Yaroslavche\SiteToolsBundle\Tests\Service
 */
class UserOnlineTest extends TestCase
{
    private UserOnline $userOnline;

    protected function setUp(): void
    {
        parent::setUp();
        $this->constructor();
    }

    private function constructor(): void
    {
        $storage = new RedisStorage(['host' => 'localhost']);
        $this->userOnline = new UserOnline($storage);
        $this->assertInstanceOf(UserOnline::class, $this->userOnline);
    }

    public function testOnlineOffline()
    {
        $user = new User('Alice');
        $this->userOnline->setOnline($user);
        $this->assertTrue($this->userOnline->isOnline($user));
        $onlineUsers = $this->userOnline->getOnlineUsers();
        $this->assertSame(1, count($onlineUsers));
        $this->assertSame(1, $this->userOnline->getOnlineCount());
        $this->assertArrayHasKey($user->getUsername(), $onlineUsers);
        $this->assertInstanceOf(\DateTimeImmutable::class, $onlineUsers['Alice']);
        $this->userOnline->setOffline($user);
        $onlineUsers = $this->userOnline->getOnlineUsers();
        $this->assertArrayNotHasKey($user->getUsername(), $onlineUsers);
        $this->assertSame(0, $this->userOnline->getOnlineCount());
        $this->assertFalse($this->userOnline->isOnline($user));
    }

    protected function tearDown(): void
    {
        # cleanup
    }
}
