<?php
declare(strict_types=1);

namespace Yaroslavche\SiteToolsBundle\Tests\Service;

use PHPUnit\Framework\TestCase;
use Yaroslavche\SiteToolsBundle\Service\UserRating;
use Yaroslavche\SiteToolsBundle\Storage\RedisStorage;
use Yaroslavche\SiteToolsBundle\Tests\Fixture\User;

/**
 * Class UserLikeTest
 * @package Yaroslavche\SiteToolsBundle\Tests\Service
 */
class UserRatingTest extends TestCase
{
    private UserRating $userRating;

    protected function setUp(): void
    {
        parent::setUp();
        $this->constructor();
    }

    private function constructor(): void
    {
        $storage = new RedisStorage(['host' => 'localhost']);
        $this->userRating = new UserRating($storage, 5);
        $this->assertInstanceOf(UserRating::class, $this->userRating);
    }

    public function testRating()
    {
        $voter = new User('Alice');
        $voter2 = new User('Charlie');
        $applicant = new User('Bob');
        $this->userRating->add($voter, $applicant, 5);
//        $ratings = $this->userRating->get($applicant);
//        $this->assertSame(1, count($ratings));
//        $this->assertSame($voter->getUsername(), $ratings[0]);
//        $this->assertSame(5, $this->userRating->average($applicant));
        $this->userRating->add($voter2, $applicant, 4);
//        $this->assertSame(4.5, $this->userRating->average($applicant));
        $this->userRating->remove($voter, $applicant);
        $ratings = $this->userRating->getRatings($applicant);
        $this->assertSame(0, count($ratings));
    }

    protected function tearDown(): void
    {
        # cleanup
    }
}
