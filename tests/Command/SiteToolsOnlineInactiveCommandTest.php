<?php
declare(strict_types=1);

namespace Yaroslavche\SiteToolsBundle\Tests\Command;

use Redis;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Security\Core\Security;
use Yaroslavche\SiteToolsBundle\Command\SiteToolsOnlineInactiveCommand;
use Yaroslavche\SiteToolsBundle\Service\UserOnline;
use Yaroslavche\SiteToolsBundle\Storage\RedisStorage;
use Yaroslavche\SiteToolsBundle\Tests\Helper;

/**
 * Class SiteToolsOnlineInactiveCommandTest
 * @package Yaroslavche\SiteToolsBundle\Tests\Command
 */
class SiteToolsOnlineInactiveCommandTest extends KernelTestCase
{
    private CommandTester $commandTester;
    private Redis $redis;
    private UserOnline $userOnline;

    protected function setUp(): void
    {
        parent::setUp();
        $application = new Application();
        $storage = new RedisStorage([]);
        $this->userOnline = new UserOnline($storage);
        $application->add(new SiteToolsOnlineInactiveCommand($this->userOnline, new Security(new Container())));

        $command = $application->find('site-tools:online:inactive');
        $this->commandTester = new CommandTester($command);
        $this->redis = new Redis();
        $this->redis->connect('localhost');
    }

    private function generateInactive(string $username): void
    {
        $this->redis->sAdd('user_online', $username);
        $this->redis->hMSet(sprintf('user_online:%s', $username), ['active' => time() - 999]);
    }

    public function testExecute()
    {
        Helper::clearOnline($this->userOnline);
        $this->generateInactive('inactive1');
        $this->commandTester->execute([
            '--gap' => 300,
            '--verbose' => false,
        ]);
        $output = $this->commandTester->getDisplay();
        $this->assertEmpty($output);
    }

    public function testExecuteVerbose()
    {
        Helper::clearOnline($this->userOnline);
        $this->generateInactive('inactive1');
        $this->commandTester->execute([
            '--gap' => 300,
            '--verbose' => true,
        ]);
        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('OK', $output);
        $this->assertStringContainsString('inactive1', $output);
        $this->assertSame(0, $this->userOnline->getOnlineCount());
    }

    public function testExecuteSmallGap()
    {
        $this->commandTester->execute([
            '--gap' => 59,
            '--verbose' => true,
        ]);
        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('[ERROR] Gap should be greater than 60 seconds.', $output);
    }
}
