<?php

namespace WeGotTickets;

use WeGotTickets\Crawler\ZendHttpClientAdapter;
use WeGotTickets\Formatter\JSONFormatter;
use WeGotTickets\Writer\Stdout;
use WeGotTickets\Scraper;

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

        $crawler = new ZendHttpClientAdapter(new \Zend_Http_Client());
        $scraper = new Scraper($crawler, new Parser(), new JSONFormatter(), new Stdout());
        $scraper->scrape($uri, 1);

    }

}