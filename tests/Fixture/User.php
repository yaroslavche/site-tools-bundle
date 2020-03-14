<?php
declare(strict_types=1);

namespace Yaroslavche\SiteToolsBundle\Tests\Fixture;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    private string $username;

    public function __construct(string $username)
    {
        $this->username = $username;
    }

    public function getRoles()
    {
    }

    public function getPassword()
    {
    }

    public function getSalt()
    {
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function eraseCredentials()
    {
    }
}
