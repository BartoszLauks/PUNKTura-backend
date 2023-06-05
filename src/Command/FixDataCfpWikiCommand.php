<?php

namespace App\Command;

use App\ProgressBar\ProgressBar;
use App\Repository\CfpEventsRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
#[AsCommand(
    name: 'app:fixDataCfpWiki',
    description: '',
)]
class FixDataCfpWikiCommand extends Command
{

    public function __construct(
        private readonly ProgressBar          $progressBar,
        private readonly CfpEventsRepository $cfpEventsRepository
    ) {
        parent::__construct();
    }


    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $range = 0;
        $cfpEvents = $this->cfpEventsRepository->findAll();
        $this->progressBar->initialize($output, count($cfpEvents));
        foreach ($cfpEvents as $event) {
            $range++;
            $matches = [];
            preg_match('/\s*\((.*?)\)\s*/', $event->getFullName(), $matches);
            if (!empty($matches)) {
                $event->setClearFullName(str_replace($matches[0], '', $event->getFullName()));
                $this->cfpEventsRepository->save($event, false);
            }

            $matches = [];
            preg_match('/\s*(\d{4})\s*/', $event->getHandle(), $matches);
            if (!empty($matches)) {
                $event->setClearHandle(str_replace($matches[0], '', $event->getHandle()));
                $this->cfpEventsRepository->save($event, false);
            }

            if ($range >= 100) {
                $this->cfpEventsRepository->flush();
                $range = 0;
            }
            $this->progressBar->advance();
            $this->cfpEventsRepository->flush();
        }
        $this->cfpEventsRepository->flush();

        return Command::SUCCESS;
    }
}
