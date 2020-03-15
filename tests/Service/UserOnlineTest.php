<?php
declare(strict_types=1);

namespace Yaroslavche\SiteToolsBundle\Tests\Service;

use DateTimeImmutable;
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
        $user2 = new User('Bob');
        $this->userOnline->setOnline($user);
        $this->assertTrue($this->userOnline->isOnline($user));
        $onlineUsers = $this->userOnline->getOnlineUsers();
        $this->assertSame(1, count($onlineUsers));
        $this->assertSame(1, $this->userOnline->getOnlineCount());
        $this->assertArrayHasKey($user->getUsername(), $onlineUsers);
        $this->assertInstanceOf(DateTimeImmutable::class, $onlineUsers['Alice']);
        $this->userOnline->setOnline($user2);
        $this->assertTrue($this->userOnline->isOnline($user2));
        $onlineUsers = $this->userOnline->getOnlineUsers();
        $this->assertSame(2, count($onlineUsers));
        $this->assertSame(2, $this->userOnline->getOnlineCount());
        $this->assertArrayHasKey($user->getUsername(), $onlineUsers);
        $this->assertArrayHasKey($user2->getUsername(), $onlineUsers);
        $this->assertInstanceOf(DateTimeImmutable::class, $onlineUsers['Alice']);
        $this->assertInstanceOf(DateTimeImmutable::class, $onlineUsers['Bob']);
        $this->userOnline->setOffline($user);
        $onlineUsers = $this->userOnline->getOnlineUsers();
        $this->assertSame(1, count($onlineUsers));
        $this->assertSame(1, $this->userOnline->getOnlineCount());
        $this->assertArrayNotHasKey($user->getUsername(), $onlineUsers);
        $this->assertFalse($this->userOnline->isOnline($user));
        $this->userOnline->setOfflineByUsername($user2->getUsername());
        $onlineUsers = $this->userOnline->getOnlineUsers();
        $this->assertSame(0, count($onlineUsers));
        $this->assertSame(0, $this->userOnline->getOnlineCount());
        $this->assertArrayNotHasKey($user2->getUsername(), $onlineUsers);
        $this->assertFalse($this->userOnline->isOnline($user2));
    }

    protected function tearDown(): void
    {
        # cleanup
    }
}
