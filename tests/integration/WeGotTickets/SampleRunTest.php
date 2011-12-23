<?php

use WeGotTickets\Scraper;
use WeGotTickets\Crawler\ZendHttpClientAdapter;
use WeGotTickets\Formatter\JSONFormatter;
use WeGotTickets\Parser;
use WeGotTickets\Writer\Stdout;

class SampleRunTest extends PHPUnit_Framework_TestCase {

    /**
     * @var \WeGotTickets\Scraper
     */
    private $scraper;

    public function setUp(){

        $crawler = new ZendHttpClientAdapter(new \Zend_Http_Client());
        $this->scraper = new Scraper($crawler, new Parser(), new JSONFormatter(), new Stdout());

    }

    /**
     * @test
     */
    public function timedCrawl(){

        $start_time = time();

        $uri = 'http://www.wegottickets.com/searchresults/page/1/all';
        $this->scraper->scrape($uri);

        $end_time = time();

        echo "Run took: " .($end_time - $start_time) . " seconds.";

    }


}