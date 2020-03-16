<?php
declare(strict_types=1);

namespace Yaroslavche\SiteToolsBundle\Tests\Storage;

use RedisException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
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
        new RedisStorage(['host' => 'invalid_host', 'timeout' => .0]);
    }

    public function testInvalidConfigKey()
    {
        $this->expectException(UndefinedOptionsException::class);
        new RedisStorage(['test' => 'string']);
    }

    public function testInvalidConfigValue()
    {
        $this->expectException(InvalidOptionsException::class);
        new RedisStorage(['port' => 'string']);
    }
}
