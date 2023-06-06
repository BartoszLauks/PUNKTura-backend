<?php

namespace App\Command;

use App\ProgressBar\ProgressBar;
use App\Repository\CfpEventsRepository;
use App\Repository\MeinEventsRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:searchEventSAndGivePoints',
    description: '',
)]
class SearchEventSAndGivePointsCommand extends Command
{
    public function __construct(
        private readonly ProgressBar          $progressBar,
        private readonly MeinEventsRepository $meinEventsRepository,
        private readonly CfpEventsRepository  $cfpEventsRepository
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $range = 0;
        $MEINEvents = $this->meinEventsRepository->findAll();
        $this->progressBar->initialize($output, count($MEINEvents));
        foreach ($MEINEvents as $event) {

            $result = $this->cfpEventsRepository->findEvents($event);
            if (!empty($result)) {
                foreach ($result as $conference) {
                    $range++;
                    $conference->setPoint($event->getPoint());
                    $this->cfpEventsRepository->save($conference, false);
                }
            }

            if ($range >= 100) {
                $this->cfpEventsRepository->flush();
                $range = 0;
            }

            $this->progressBar->advance();
        }
        $this->cfpEventsRepository->flush();

        return Command::SUCCESS;
    }
}
