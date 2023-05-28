<?php

namespace App\Controller;

use App\Enum\Categories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public function __construct(
        private Categories $categories
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
}
