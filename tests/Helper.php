<?php
declare(strict_types=1);

namespace Yaroslavche\SiteToolsBundle\Tests;

use Yaroslavche\SiteToolsBundle\Service\UserOnline;
use Yaroslavche\SiteToolsBundle\Service\UserRating;

/**
 * Class Helper
 * @package Yaroslavche\SiteToolsBundle\Tests
 */
class Helper
{
    public static function clearOnline(UserOnline $userOnline): void
    {
        foreach ($userOnline->getOnlineUsers() as $username => $active) {
            $userOnline->setOfflineByUsername($username);
        }
    }
}
