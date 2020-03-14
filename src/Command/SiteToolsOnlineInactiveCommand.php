<?php
declare(strict_types=1);

namespace Yaroslavche\SiteToolsBundle\Command;

use DateTimeImmutable;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Security;
use Yaroslavche\SiteToolsBundle\Service\Online;

/**
 * Class SiteToolsOnlineInactiveCommand
 * @package Yaroslavche\SiteToolsBundle\Command
 */
class SiteToolsOnlineInactiveCommand extends Command
{
    protected static $defaultName = 'site-tools:online:inactive';
    private Online $onlineService;
    private Security $security;

    /**
     * SiteToolsOnlineInactive constructor.
     * @param Online $onlineService
     * @param Security $security
     */
    public function __construct(Online $onlineService, Security $security)
    {
        parent::__construct();
        $this->onlineService = $onlineService;
        $this->security = $security;
    }


    protected function configure()
    {
        $this
            ->setDescription('Start socket server with TDLib JsonClient')
            ->addOption('gap', null, InputOption::VALUE_OPTIONAL, 'Gap in seconds', 300);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $gap = $input->getOption('gap');
        $onlineUsers = $this->onlineService->getOnlineUsers();
        $now = (new DateTimeImmutable())->getTimestamp();
        $usernames = [];
        /**
         * @var string $username
         * @var DateTimeImmutable $active
         */
        foreach ($onlineUsers as $username => $active) {
            $seconds = $now - $active->getTimestamp();
            if ($seconds > $gap) {
                $this->onlineService->setOffline($username);
                $usernames[] = $username;
            }
        }
        if (!empty($usernames)) {
            $io->note(sprintf('Inactive more than %s seconds:', $gap));
            $io->listing($usernames);
        }
        $io->success('Finished');

        return 0;
    }
}
