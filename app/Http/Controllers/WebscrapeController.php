<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class WebscrapeController extends Controller
{
    private $results = array();
    private$longTitleEntries = array();
    private $smallTitleEntries = array();

    public function scraper()
    {
        //setup
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
                $this->scrapeInfo($entry);
        });

        return response()->json(
            [
                'entries' => $this->results,
                'status' => $html->getStatusCode(),
            ]
        );
    }

    private function filterResults(string $title, string $rank, string $numberOfComments, string $points): Array
    {
        $wordsInTitle = str_word_count($title, 0);
        //Filter all previous entries with more than five words in the title ordered by the number of comments first.
        if ($wordsInTitle > 5) {
            $this->longTitleEntries[] = [
                'rank' => $rank,
                'title' => $title,
                'points' => $points,
                'comments' => $numberOfComments,
                ];
            //desc order for comments
            usort($this->longTitleEntries, function ($a, $b) {
                return $b['comments'] <=> $a['comments'];
            });
        } else {
        // Filter all previous entries with less than or equal to five words in the title ordered by points.
            $this->smallTitleEntries[] = [
                'rank' => $rank,
                'title' => $title,
                'points' => $points,
                'comments' => $numberOfComments,
                ];
            //desc order for points
            usort($this->smallTitleEntries, function ($a, $b) {
                return $b['points'] <=> $a['points'];
            });
        } 

        $this->results = array_merge($this->longTitleEntries, $this->smallTitleEntries);
        return $this->results;
    }

    private function scrapeInfo(Crawler $entry)
    {
        $nextElement = $entry->nextAll()->filter('tr')->first();
        $title = $entry->filter('td.title span.titleline')->text();
        $rank = $entry->filter('td.title span.rank')->text();

        if ($nextElement->filter('td.subtext span.subline')->count() > 0) {
            $numberOfComments = filter_var($nextElement->filter('td.subtext span.subline a:contains("comments"), a:contains("discuss"), a:contains("comment")')->text(), FILTER_SANITIZE_NUMBER_INT);
            $points = filter_var($nextElement->filter('td.subtext span.score')->text(), FILTER_SANITIZE_NUMBER_INT);
        } else {
            $numberOfComments = 0;
            $points = 0;
        }

        //apply filters
        $this->filterResults($title, $rank, $numberOfComments, $points);
    }
}
