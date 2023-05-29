<?php

namespace App\Controller;

use App\Entity\CfpEvents;
use App\Enum\Categories;
use App\Repository\CfpEventsRepository;
use DateTime;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
     const PAGE = '&page=';

    public function __construct(
        private Categories $categories,
        private CfpEventsRepository $cfpEventsRepository
    ) {
    }

    #[Route('/home', name: 'app_home')]
    public function index(): JsonResponse
    {
//        foreach (Categories::availableValue() as $item) {
//            dd($this->categories->getURL($item));
//        }

        $client = HttpClient::create();

        //$response = $client->request('GET', 'http://www.wikicfp.com/cfp/call?conference=artificial%20intelligence');
        $response = $client->request('GET', 'http://www.wikicfp.com/cfp/call?conference=conferences');

        // Pobranie zawartości strony
        $htmlContent = $response->getContent();

        // Tworzenie obiektu Crawler dla analizy strony
        $crawler = new Crawler($htmlContent);

        // Wybranie tabeli na stronie
        $table = $crawler->filter('form')->slice(1);

//        $last = '';
//        $table->filter('tr')->slice(8)->last()->each(function (Crawler $elemt, $i) {
//            global $last = $elemt->text();
//        });
        $table->filter('tr')->slice(8, -2)->each(function (Crawler $row, $i) {
            //if ($row->text() == null) dd("ta");
            echo "Wiersz : ".$i;
            echo "\n";
            $columns = $row->filter('td')->each(function (Crawler $column, $j ) {
                if ($column->text() == 'Expired CFPs') {
                    dd("dupa");
                }
                echo "Kolumna : ".$j." ".$column->text();
                echo "\n";
            });

//            foreach ($columns as $column) {
//                echo $column. "\n";
//            }
        });




        // Przetwarzanie danych z tabeli
//        $table->filter('td')->slice()->each(function (Crawler $row, $i) {
//            // Pobranie danych z poszczególnych kolumn
//            $columns = $row->filter('tr')->each(function (Crawler $column, $j) {
//                return $column->text();
//            });
//
//            // Wyświetlenie danych
//            echo "Wiersz $i:\n";
//            foreach ($columns as $column) {
//                echo $column . "\n";
//            }
//            echo "\n";
//        });

        return $this->json([]);
    }

    #[Route('/links', name: 'app_links')]
    public function links() : JsonResponse
    {
        $list= [];
        $client = HttpClient::create();

        $response = $client->request('GET', 'http://www.wikicfp.com/cfp/allcat');

        // Pobranie zawartości strony
        $htmlContent = $response->getContent();

        // Tworzenie obiektu Crawler dla analizy strony
        $crawler = new Crawler($htmlContent);

        $links = $crawler->filter('a')->extract(['href']);

        $result = array_filter($links, function($element) {
            return str_contains($element, "/cfp/call?conference");
        });

        $result = array_map('strtoupper', $result);
        dd($result);
    }

    #[Route('/test', name: 'app_test')]
    public function test() : JsonResponse
    {
        $data = [];
        $client = HttpClient::create();
        $categories = Categories::availableValue();

        foreach ($categories as $category) {
            $link = $this->categories->getURL($category);
            for ($i = 1; $i <= 2; $i++) {
                //if ($i >= 2)  dd(array_chunk($data, 6));
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
                $cfpEvents = new CfpEvents();
                $cfpEvents->setCfpLink($conference[0]);
                $cfpEvents->setHandle($conference[1]);
                $cfpEvents->setFullName($conference[2]);
                //$cfpEvents->setSubmitDate();
                $cfpEvents->setLocation($conference[4]);
                $cfpEvents->setSubmitDate($conference[5]);
                $cfpEvents->setSubmitDateFormat(DateTimeImmutable::createFromFormat("M d, Y", $conference[5]));
                dd(DateTime::createFromFormat("M d, Y", $conference[5]));
            }


            dd("dupa");
        }

        return $this->json('test');
    }

    private static function simulateHumanDivision() : void
    {
        sleep(rand(5, 10));
    }
}
