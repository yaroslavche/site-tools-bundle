<?php
declare(strict_types=1);

namespace Yaroslavche\SiteToolsBundle\DataCollector;

use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Throwable;
use Yaroslavche\SiteToolsBundle\Service\UserOnline;

/**
 * Class SiteToolsCollector
 * @package Yaroslavche\SiteToolsBundle\DataCollector
 */
class SiteToolsCollector extends DataCollector
{
    public const DATA_COLLECTOR_NAME = 'yaroslavche_site_tools.data_collector.site_tools';
    private UserOnline $onlineService;

    /**
     * SiteToolsCollector constructor.
     * @param UserOnline $onlineService
     */
    public function __construct(UserOnline $onlineService)
    {
        $this->onlineService = $onlineService;
    }

    /**
     * Collects data for the given Request and Response.
     * @param Request $request
     * @param Response $response
     * @param Throwable|null $exception
     */
    public function collect(Request $request, Response $response, ?Throwable $exception = null): void
    {
        $onlineUsers = $this->onlineService->getOnlineUsers();
        $this->data['online'] = $onlineUsers;
    }

    /**
     * Returns the name of the collector.
     * @return string The collector name
     */
    public function getName()
    {
        return static::DATA_COLLECTOR_NAME;
    }

    public function reset(): void
    {
        $this->data = [];
    }

    /**
     * @return array<string, DateTimeImmutable>
     */
    public function getOnline(): array
    {
        return $this->data['online'];
    }
}
