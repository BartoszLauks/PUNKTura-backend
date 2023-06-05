<?php

namespace App\Command;

use App\ProgressBar\ProgressBar;
use App\Repository\MeinEventsRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:addHandleFixFullName',
    description: '',
)]
class AddHandleFixFullNameCommand extends Command
{

    public function __construct(
        private readonly MeinEventsRepository $MEiNEventsRepository,
        private readonly ProgressBar          $progressBar
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $range = 0;
        $MEINEvents = $this->MEiNEventsRepository->findAll();
        $this->progressBar->initialize($output, count($MEINEvents));
        foreach ($MEINEvents as $event) {
            $range++;
            $matches = [];
            preg_match('/\[(.*?)\]/', $event->getFullName(), $matches);

            if (!empty($matches)) {
                $event->setHandle($matches[1]);
                $event->setFullName(str_replace(" ".$matches[0], '', $event->getFullName()));
                $this->MEiNEventsRepository->save($event, false);
            }

            if ($range >= 100) {
                $this->MEiNEventsRepository->flush();
                $range = 0;
            }
            $this->progressBar->advance();
        }
        $this->MEiNEventsRepository->flush();

        return Command::SUCCESS;
    }
}
