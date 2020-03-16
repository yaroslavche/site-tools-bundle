<?php
declare(strict_types=1);

namespace Yaroslavche\SiteToolsBundle\Tests\DataCollector;

use DateTimeImmutable;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Yaroslavche\SiteToolsBundle\DataCollector\SiteToolsCollector;
use Yaroslavche\SiteToolsBundle\Service\UserOnline;
use Yaroslavche\SiteToolsBundle\Storage\RedisStorage;
use Yaroslavche\SiteToolsBundle\Tests\Fixture\User;

/**
 * Class SiteToolsCollectorTest
 * @package Yaroslavche\SiteToolsBundle\Tests\DataCollector
 */
class SiteToolsCollectorTest extends TestCase
{
    public function testCollector()
    {
        $storage = new RedisStorage([]);
        $userOnline = new UserOnline($storage);
        $userOnline->setOnline(new User('Alice'));
        $collector = new SiteToolsCollector($userOnline);
        $this->assertSame($collector->getName(), SiteToolsCollector::DATA_COLLECTOR_NAME);
        $request = new Request();
        $response = new Response();
        $collector->collect($request, $response);
        $onlineUsers = $collector->getOnline();
        $this->assertSame(1, count($onlineUsers));
        $this->assertArrayHasKey('Alice', $onlineUsers);
        $this->assertInstanceOf(DateTimeImmutable::class, $onlineUsers['Alice']);
        $userOnline->setOfflineByUsername('Alice');
        $collector->reset();
        try {
            $collector->getOnline();
        } catch (Exception $exception) {
            $this->assertSame($exception->getMessage(), 'Undefined index: online');
        }
    }
}
