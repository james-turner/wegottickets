<?php

namespace WeGotTickets;

class App {

    static public function run($argv){

        $uri = 'http://www.wegottickets.com/searchresults/page/1/all';

        // parse cmd line args.
        if(count($argv) > 1){
            if(false === parse_url($argv[1])){
                throw new \RuntimeException("Check that the supplied URI via cmd line is a valid URI.");
            }
            $uri = $argv[1];
        }

        $spider = new \WeGotTickets\Crawler\ZendHttpClientAdapter(new \Zend_Http_Client());
        $html = $spider->crawl($uri);

        $scraper = new \WeGotTickets\Scraper(new \WeGotTickets\Formatter\JSONFormatter());

        $writer = new Writer\Stdout();

        $writer->write($scraper->scrapeListings($html));



    }

}