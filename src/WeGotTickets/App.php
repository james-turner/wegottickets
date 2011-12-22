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
            $uri = $argv[0];
        }

        $spider = new \WeGotTickets\Spider(new \Zend_Http_Client());
        $html = $spider->crawl($uri);

        $scraper = new \WeGotTickets\Scraper();

        $json = $scraper->scrapeListings($html);
        echo $json;

    }

}