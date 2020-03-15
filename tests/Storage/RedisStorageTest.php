<?php
declare(strict_types=1);

namespace Yaroslavche\SiteToolsBundle\Tests\Storage;

use RedisException;
use Yaroslavche\SiteToolsBundle\Storage\RedisStorage;
use PHPUnit\Framework\TestCase;

/**
 * Class RedisStorageTest
 * @package Yaroslavche\SiteToolsBundle\Tests\Storage
 */
class RedisStorageTest extends TestCase
{

    public function testInvalidConstruct()
    {
        $this->expectException(RedisException::class);
        $storage = new RedisStorage(['host' => 'invalid_host', 'timeout' => 0.1]);
    }
}
