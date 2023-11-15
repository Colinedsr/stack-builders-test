<?php

namespace Tests\Unit;

use App\Http\Controllers\WebscrapeController;
use GuzzleHttp\Client;
use Tests\TestCase;

class WebscrapeControllerTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function testFilterLongTitleResults(): void
    {
        $expectedResult = [
            [
                'rank' => "5.",
                'title' => "GraphCast: AI model for weather forecasting",
                'points' => "323",
                'comments' => "323",
            ],
            [
                'rank' => "1.",
                'title' => "Hacking ADHD: Strategies for the modern developer",
                'points' => "198",
                'comments' => "161",
            ]
        ];
        $entries = [
            [
                'rank' => "1.",
                'title' => "Hacking ADHD: Strategies for the modern developer",
                'points' => "198",
                'comments' => "161",
            ],
            [
                'rank' => "5.",
                'title' => "GraphCast: AI model for weather forecasting",
                'points' => "323",
                'comments' => "323",
            ]
        ];

        $clientMock = $this->createMock(Client::class);
        $webscrapeController = new WebscrapeController($clientMock);

        foreach ($entries as $entry) {
            $results = $webscrapeController->filterResults($entry['title'], $entry['rank'], $entry['comments'], $entry['points']);
        }

        $this->assertEquals($expectedResult, $results);
    }

    public function testFilterShortTitleResults(): void
    {
        $expectedResult = [
            [
                'rank' => "5.",
                'title' => "GraphCast:",
                'points' => "323",
                'comments' => "323",
            ],
            [
                'rank' => "1.",
                'title' => "Strategies for the modern developer",
                'points' => "198",
                'comments' => "161",
            ],
            [
                'rank' => "2.",
                'title' => "Hacking ADHD",
                'points' => "19",
                'comments' => "161",
            ]
        ];
        $entries = [
            [
                'rank' => "1.",
                'title' => "Strategies for the modern developer",
                'points' => "198",
                'comments' => "161",
            ],
            [
                'rank' => "2.",
                'title' => "Hacking ADHD",
                'points' => "19",
                'comments' => "161",
            ],
            [
                'rank' => "5.",
                'title' => "GraphCast:",
                'points' => "323",
                'comments' => "323",
            ],
        ];

        $clientMock = $this->createMock(Client::class);
        $webscrapeController = new WebscrapeController($clientMock);

        foreach ($entries as $entry) {
            $results = $webscrapeController->filterResults($entry['title'], $entry['rank'], $entry['comments'], $entry['points']);
        }

        $this->assertEquals($expectedResult, $results);
    } 
}
