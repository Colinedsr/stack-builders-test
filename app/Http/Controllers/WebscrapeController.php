<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class WebscrapeController extends Controller
{
    private $results = array();
    public function scraper()
    {
        $client = new Client();
        $url = 'https://news.ycombinator.com/';
        $html = $client->request('GET', $url);

        $crawler = new Crawler($html->getBody());
        $counter = 0;

        // scrap website 
        $crawler->filter('tr.athing')->each(function (Crawler $entry, $index) use ($counter) {
                if ($counter >= 30) {
                    return false;
                }
                $counter++;
                $nextElement = $entry->nextAll()->filter('tr')->first();

                if ($nextElement->filter('td.subtext span.subline')->count() > 0) {
                    $comments = $nextElement->filter('td.subtext span.subline a:contains("comments"), a:contains("discuss"), a:contains("comment")')->text();
                    $points = $nextElement->filter('td.subtext span.score')->text();
                } else {
                    $comments = '';
                    $points = 0;
                }
                array_push($this->results, [
                    'rank' => $entry->filter('td.title span.rank')->text(),
                    'title' => $entry->filter('td.title span.titleline')->text(),
                    'points' => $points,
                    'comments' => $comments,
                ]
                );
        });
        return response()->json(
            [
                'entries' => $this->results,
                'status' => $html->getStatusCode(),
            ]
        );
    }
}
