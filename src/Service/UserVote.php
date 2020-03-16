<?php
declare(strict_types=1);

namespace Yaroslavche\SiteToolsBundle\Service;

use Symfony\Component\Security\Core\User\UserInterface;
use Yaroslavche\SiteToolsBundle\Storage\StorageInterface;

/**
 * Class UserVote
 * @package Yaroslavche\SiteToolsBundle\Service
 */
class UserVote
{
    private StorageInterface $storage;

    /**
     * UserVote constructor.
     * @param StorageInterface $storage
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param UserInterface $voterUser
     * @param UserInterface $applicantUser
     */
    public function up(UserInterface $voterUser, UserInterface $applicantUser): void
    {
        $this->storage->addUpVote($voterUser, $applicantUser);
    }

    /**
     * @param UserInterface $voterUser
     * @param UserInterface $applicantUser
     */
    public function down(UserInterface $voterUser, UserInterface $applicantUser): void
    {
        $this->storage->addDownVote($voterUser, $applicantUser);
    }

    /**
     * @param UserInterface $voterUser
     * @param UserInterface $applicantUser
     */
    public function remove(UserInterface $voterUser, UserInterface $applicantUser): void
    {
        $this->storage->removeVote($voterUser, $applicantUser);
    }

    /**
     * @param UserInterface $user
     * @return array<string>
     */
    public function get(UserInterface $user): array
    {
        return $this->storage->getVotes($user);
    }

    /**
     * @param UserInterface $user
     * @return int
     */
    public function getValue(UserInterface $user): int
    {
        return $this->storage->getVotesValue($user);
    }
}
