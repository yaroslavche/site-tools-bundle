<?php
declare(strict_types=1);

namespace Yaroslavche\SiteToolsBundle\Tests\Service;

use PHPUnit\Framework\TestCase;
use Yaroslavche\SiteToolsBundle\Service\UserLike;
use Yaroslavche\SiteToolsBundle\Storage\RedisStorage;
use Yaroslavche\SiteToolsBundle\Tests\Fixture\User;

/**
 * Class UserLikeTest
 * @package Yaroslavche\SiteToolsBundle\Tests\Service
 */
class UserLikeTest extends TestCase
{
    private UserLike $userLike;

    protected function setUp(): void
    {
        parent::setUp();
        $this->constructor();
    }

    private function constructor(): void
    {
        $storage = new RedisStorage(['host' => 'localhost']);
        $this->userLike = new UserLike($storage);
        $this->assertInstanceOf(UserLike::class, $this->userLike);
    }

    public function testLikeUnlike()
    {
        $voter = new User('Alice');
        $applicant = new User('Bob');
        $this->userLike->like($voter, $applicant);
        $likes = $this->userLike->get($applicant);
        $this->assertSame(1, count($likes));
        $this->assertSame($voter->getUsername(), $likes[0]);
        $this->userLike->unlike($voter, $applicant);
        $likes = $this->userLike->get($applicant);
        $this->assertSame(0, count($likes));
    }

    protected function tearDown(): void
    {
        # cleanup
    }
}
