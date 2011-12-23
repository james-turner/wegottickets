<?php

namespace WeGotTickets;

use WeGotTickets\Formatter\Formatter;
use WeGotTickets\Parser;
use WeGotTickets\Writer\Writer;
use WeGotTickets\Crawler\Crawler;

class Scraper {

    private $crawler;
    private $formatter;
    private $writer;
    private $parser;

    public function __construct(Crawler $crawler, Parser $parser, Formatter $formatter, Writer $writer){
        $this->crawler = $crawler;
        $this->parser = $parser;
        $this->formatter = $formatter;
        $this->writer = $writer;
    }

    public function scrape($uri, $limit = 0){

        $html = $this->crawler->crawl($uri);

        $indices = $this->parser->parseIndices($html);

        // If a page further along the indices is provided slice the indices to that point.
        if(false !== $primary_key = array_search($uri, $indices)){
            $indices = array_slice($indices, $primary_key);
        }

        if(0 === $limit) $limit = count($indices);

        $listings = array();

        while(null !== ($index = array_shift($indices)) && !($limit-- <= 0)){
            $html = $this->crawler->crawl($index);
            $listings = array_merge($listings, $this->parser->parseListings($html));
        }

        $formatted = $this->formatter->format($listings);

        $this->writer->write($formatted);
    }

}