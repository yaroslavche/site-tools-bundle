<?php
declare(strict_types=1);

namespace Yaroslavche\SiteToolsBundle\Command;

use DateTimeImmutable;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Yaroslavche\SiteToolsBundle\Service\UserOnline;

/**
 * Class SiteToolsOnlineInactiveCommand
 * @package Yaroslavche\SiteToolsBundle\Command
 */
class SiteToolsOnlineInactiveCommand extends Command
{
    protected static $defaultName = 'site-tools:online:inactive';
    private UserOnline $onlineService;

    /**
     * SiteToolsOnlineInactive constructor.
     * @param UserOnline $onlineService
     */
    public function __construct(UserOnline $onlineService)
    {
        parent::__construct();
        $this->onlineService = $onlineService;
    }


    protected function configure(): void
    {
        $this
            ->setDescription('Make offline inactive users')
            ->addOption('gap', 'g', InputOption::VALUE_OPTIONAL, 'Gap in seconds', 300);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $verbose = $input->getOption('verbose');
        $gap = intval($input->getOption('gap'));
        if ($gap < 60) {
            if ($verbose) {
                $io->error('Gap should be greater than 60 seconds.');
            }
            return 1;
        }
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
                $this->onlineService->setOfflineByUsername($username);
                $usernames[] = $username;
            }
        }
        if (!empty($usernames) && $verbose) {
            $io->note(sprintf('Inactive more than %s seconds:', $gap));
            $io->listing($usernames);
        }
        if ($verbose) {
            $io->success('Finished');
        }

        return 0;
    }
}
