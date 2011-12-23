<?php

use WeGotTickets\Scraper;
use WeGotTickets\Crawler\ZendHttpClientAdapter;
use WeGotTickets\Formatter\JSONFormatter;

class SampleRunTest extends PHPUnit_Framework_TestCase {

    /**
     * @var \WeGotTickets\Spider
     */
    private $spider;

    /**
     * @var \WeGotTickets\Scraper
     */
    private $scraper;

    public function setUp(){

        $this->spider = new ZendHttpClientAdapter(new Zend_Http_Client());
        $this->scraper = new Scraper(new JSONFormatter());

    }

    /**
     * @test
     */
    public function timedCrawl(){

        $start_time = time();

        $start_page = $this->spider->crawl('http://www.wegottickets.com/searchresults/page/1/all');

        $indices = $this->scraper->scrapeIndices($start_page);

        while(null !== ($index = array_shift($indices))){
            $html = $this->spider->crawl($index);
            $this->scraper->scrapeListings($html);
        }

        $end_time = time();


        var_dump(($end_time - $start_time)/60);

    }


}