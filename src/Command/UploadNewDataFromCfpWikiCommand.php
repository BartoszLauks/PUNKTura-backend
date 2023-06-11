<?php

namespace App\Command;

use App\Entity\CfpEvents;
use App\Enum\Categories;
use App\Message\Conferences;
use App\ProgressBar\ProgressBar;
use App\Repository\CfpEventsRepository;
use DateTimeImmutable;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:uploadNewDataFromCfpWikiCommand',
    description: '',
)]
class UploadNewDataFromCfpWikiCommand extends Command
{
    const HOME = 'http://www.wikicfp.com/cfp/home';

    public function __construct(
        private readonly Categories          $categories,
        private readonly CfpEventsRepository $cfpEventsRepository,
        private readonly ProgressBar $progressBar,
        private readonly MessageBusInterface $messageBus
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $data = [];
        $client = HttpClient::create();
        self::simulateHumanDivision();
        $response = $client->request('GET', self::HOME);
        $htmlContent = $response->getContent();
        $crawler = new Crawler($htmlContent);
        $table = $crawler->filter('form')->slice(1);
        $table->filter('tr')->slice(5)->each(function (Crawler $row) use (&$data) {
           $columns = $row->filter('td')->each(function (Crawler $column) use (&$data) {
               $linkEvent = $column->filter('a')->extract(['href']);
               if (!empty($linkEvent)) {
                   $data[] = $linkEvent[0];
               }
               if (!empty($column->text())) {
                   $data[] = $column->text();
               }
           });
        });
        if (empty($data)) return Command::SUCCESS;
        if (count($data) % 6 != 0) throw new HttpException(Response::HTTP_BAD_REQUEST, "The data is not compatible");
        $this->messageBus->dispatch(new Conferences(array_chunk($data, 6)));

        return Command::SUCCESS;
    }

    private static function simulateHumanDivision() : void
    {
        sleep(rand(5, 10));
    }
}
