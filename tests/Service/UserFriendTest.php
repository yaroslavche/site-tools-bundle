<?php
declare(strict_types=1);

namespace Yaroslavche\SiteToolsBundle\Tests\Service;

use PHPUnit\Framework\TestCase;
use Yaroslavche\SiteToolsBundle\Service\UserFriend;
use Yaroslavche\SiteToolsBundle\Storage\RedisStorage;
use Yaroslavche\SiteToolsBundle\Tests\Fixture\User;

/**
 * Class UserLikeTest
 * @package Yaroslavche\SiteToolsBundle\Tests\Service
 */
class UserFriendTest extends TestCase
{
    private UserFriend $userFriend;

    protected function setUp(): void
    {
        parent::setUp();
        $this->constructor();
    }

    private function constructor(): void
    {
        $storage = new RedisStorage(['host' => 'localhost']);
        $this->userFriend = new UserFriend($storage);
        $this->assertInstanceOf(UserFriend::class, $this->userFriend);
    }

    public function testFriendUnfriend()
    {
        $alice = new User('Alice');
        $bob = new User('Bob');
        $this->userFriend->add($alice, $bob);
        $aliceFriends = $this->userFriend->get($alice);
        $bobFriends = $this->userFriend->get($bob);
        $this->assertSame(1, count($aliceFriends));
        $this->assertSame(1, count($bobFriends));
        $this->assertSame($alice->getUsername(), $bobFriends[0]);
        $this->assertSame($bob->getUsername(), $aliceFriends[0]);
        $this->assertTrue($this->userFriend->isFriend($bob, $alice));
        $this->assertTrue($this->userFriend->isFriend($alice, $bob));
        $this->userFriend->remove($bob, $alice);
        $aliceFriends = $this->userFriend->get($alice);
        $bobFriends = $this->userFriend->get($bob);
        $this->assertSame(0, count($aliceFriends));
        $this->assertSame(0, count($bobFriends));
        $this->assertFalse($this->userFriend->isFriend($bob, $alice));
        $this->assertFalse($this->userFriend->isFriend($alice, $bob));
    }

    protected function tearDown(): void
    {
        # cleanup
    }
}
