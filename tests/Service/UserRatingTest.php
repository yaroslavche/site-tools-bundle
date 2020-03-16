<?php
declare(strict_types=1);

namespace Yaroslavche\SiteToolsBundle\Tests\Service;

use PHPUnit\Framework\TestCase;
use Yaroslavche\SiteToolsBundle\Exception\InvalidRatingRangeException;
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

    public function invalidRating()
    {
        yield [-1];
        yield [PHP_INT_MAX];
    }

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
        $alice = new User('Alice');
        $bob = new User('Bob');
        $charlie = new User('Charlie');

        $ratings = $this->userRating->getRatings($bob);
        $this->assertSame(0, count($ratings));
        $this->assertSame(.0, $this->userRating->getRating($bob));

        $this->userRating->add($alice, $bob, 5);
        $ratings = $this->userRating->getRatings($bob);
        $this->assertArrayHasKey('Alice', $ratings);
        $this->assertArrayNotHasKey('Charlie', $ratings);
        $this->assertSame(1, count($ratings));
        $this->assertSame(5.0, $this->userRating->getRating($bob));

        $this->userRating->add($charlie, $bob, 4);
        $ratings = $this->userRating->getRatings($bob);
        $this->assertArrayHasKey('Alice', $ratings);
        $this->assertArrayHasKey('Charlie', $ratings);
        $this->assertSame(2, count($ratings));
        $this->assertSame(4.5, $this->userRating->getRating($bob));

        $this->userRating->remove($alice, $bob);
        $ratings = $this->userRating->getRatings($bob);
        $this->assertArrayNotHasKey('Alice', $ratings);
        $this->assertArrayHasKey('Charlie', $ratings);
        $this->assertSame(1, count($ratings));
        $this->assertSame(4.0, $this->userRating->getRating($bob));

        $this->userRating->remove($charlie, $bob);
        $ratings = $this->userRating->getRatings($bob);
        $this->assertSame(0, count($ratings));
        $this->assertSame(.0, $this->userRating->getRating($bob));
    }

    /**
     * @dataProvider invalidRating
     */
    public function testInvalidRatingRange($rating)
    {
        $voter = new User('Alice');
        $applicant = new User('Bob');
        $this->expectException(InvalidRatingRangeException::class);
        $this->userRating->add($voter, $applicant, $rating);
    }

    protected function tearDown(): void
    {
        # tmp solution, remove ratings
        $alice = new User('Alice');
        $bob = new User('Charlie');
        $charlie = new User('Bob');
        $this->userRating->remove($alice, $charlie);
        $this->userRating->remove($bob, $charlie);
    }
}
