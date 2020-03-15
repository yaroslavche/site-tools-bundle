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

    public function testLikeUnlike()
    {
        $voter = new User('Alice');
        $applicant = new User('Bob');
        $this->userFriend->add($voter, $applicant);
        $friends = $this->userFriend->get($applicant);
        $this->assertSame(1, count($friends));
        $this->assertSame($voter->getUsername(), $friends[0]);
        $this->userFriend->remove($voter, $applicant);
        $friends = $this->userFriend->get($applicant);
        $this->assertSame(0, count($friends));
    }

    protected function tearDown(): void
    {
        # cleanup
    }
}
