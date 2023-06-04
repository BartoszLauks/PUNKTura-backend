<?php

namespace App\Command;

use App\Entity\CfpEvents;
use App\Enum\Categories;
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

#[AsCommand(
    name: 'app:uploadDataFromCfpWiki',
    description: '',
)]
class UploadDataFromCfpWikiCommand extends Command
{
    const PAGE = '&page=';

    public function __construct(
        private readonly Categories          $categories,
        private readonly CfpEventsRepository $cfpEventsRepository,
        private readonly ProgressBar $progressBar
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $client = HttpClient::create();
        $categories = Categories::availableValue();
        $this->progressBar->initialize($output, count($categories));
        foreach ($categories as $category) {
            $link = $this->categories->getURL($category);
            for ($i = 1; $i <= 2; $i++) {
                $response = $client->request('GET', $link.self::PAGE.$i);
                self::simulateHumanDivision();
                $htmlContent = $response->getContent();
                $crawler = new Crawler($htmlContent);
                $table = $crawler->filter('form')->slice(1);
                $table->filter('tr')->slice(8, -2)->each(function (Crawler $row) use (&$data) {
                    $columns = $row->filter('td')->each(function (Crawler $column) use (&$data) {
                        if ($column->text() == 'Expired CFPs') return;
                        $linkEvent = $column->filter('a')->extract(['href']);
                        if (!empty($linkEvent)) {
                            $data[] = $linkEvent[0];
                        }
                        if (!empty($column->text())) {
                            $data[] = $column->text();
                        }
                    });
                });
            }
            if (empty($data)) continue;
            if (count($data) % 6 != 0) throw new HttpException("The data is not compatible", Response::HTTP_BAD_REQUEST);
            $conferences = array_chunk($data, 6);
            foreach ($conferences as $conference) {
                if ($this->cfpEventsRepository->findOneBy(['handle' => $conference[1], 'fullName' => $conference[2]])) {
                    continue;
                }
                $cfpEvents = new CfpEvents();
                $cfpEvents->setCfpLink($conference[0]);
                $cfpEvents->setHandle($conference[1]);
                $cfpEvents->setFullName($conference[2]);
                $cfpEvents->setLocation($conference[4]);
                $cfpEvents->setSubmitDate($conference[5]);
                if ($conference[5]) {
                    $matches = [];
                    preg_match( '/(\w{3} \d{1,2}, \d{4})/', $conference[5], $matches);
                    if (!empty($matches)) {
                        $cfpEvents->setSubmitDateFormat(DateTimeImmutable::createFromFormat("M d, Y", $matches[0]));
                    }
                }
                if ($conference[1]) {
                    $matches = [];
                    preg_match( '/(\d{4})/', $conference[1], $matches);
                    if (!empty($matches)) {
                        $cfpEvents->setYear($matches[0]);
                    }
                }

                if ($conference[3]) {
                    $matches = [];
                    preg_match_all( '/(\w{3} \d{1,2}, \d{4})/', $conference[3], $matches);
                    if (!empty($matches[0])) {
                        $cfpEvents->setBeginDate($matches[0][0]);
                        $cfpEvents->setBeginDateFormat(DateTimeImmutable::createFromFormat("M d, Y", $matches[0][0]));
                        $cfpEvents->setFinishDate($matches[0][1]);
                        $cfpEvents->setFinishDateFormat(DateTimeImmutable::createFromFormat("M d, Y", $matches[0][1]));
                    }
                }
                $this->cfpEventsRepository->save($cfpEvents, false);
            }
            $this->cfpEventsRepository->flush();

            $this->progressBar->advance();
        }
        return Command::SUCCESS;
    }

    private static function simulateHumanDivision() : void
    {
        sleep(rand(5, 10));
    }
}
