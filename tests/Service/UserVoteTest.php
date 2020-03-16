<?php
declare(strict_types=1);

namespace Yaroslavche\SiteToolsBundle\Tests\Service;

use PHPUnit\Framework\TestCase;
use Yaroslavche\SiteToolsBundle\Service\UserVote;
use Yaroslavche\SiteToolsBundle\Storage\RedisStorage;
use Yaroslavche\SiteToolsBundle\Tests\Fixture\User;

/**
 * Class UserVoteTest
 * @package Yaroslavche\SiteToolsBundle\Tests\Service
 */
class UserVoteTest extends TestCase
{
    private UserVote $userVote;

    protected function setUp(): void
    {
        parent::setUp();
        $this->constructor();
    }

    private function constructor(): void
    {
        $storage = new RedisStorage(['host' => 'localhost']);
        $this->userVote = new UserVote($storage);
        $this->assertInstanceOf(UserVote::class, $this->userVote);
    }

    public function testLikeUnlike()
    {
        $voter = new User('Alice');
        $applicant = new User('Bob');
        $this->userVote->up($voter, $applicant);
        $votes = $this->userVote->get($applicant);
        $voteValue = $this->userVote->getValue($applicant);
//        $this->assertSame(1, count($votes));
//        $this->assertSame($voter->getUsername(), $votes[0]);
        $this->userVote->down($voter, $applicant);
        $votes = $this->userVote->get($applicant);
        $voteValue = $this->userVote->getValue($applicant);
//        $this->assertSame(1, count($votes));
        $this->userVote->remove($voter, $applicant);
        $votes = $this->userVote->get($applicant);
        $voteValue = $this->userVote->getValue($applicant);
//        $this->assertSame(0, count($votes));
    }

    protected function tearDown(): void
    {
        # cleanup
    }
}
