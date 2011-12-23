<?php

namespace WeGotTickets;

use WeGotTickets\Formatter\Formatter;

class Scraper {

    /**
     * @var \WeGotTickets\Formatter\Formatter
     */
    private $formatter;

    public function __construct(Formatter $formatter){
        // Use internal errors.
        $this->formatter = $formatter;

        libxml_use_internal_errors(true);
    }

    public function scrapeIndices($html){

        $doc = new \DOMDocument();
        $doc->loadHTML($html);

        $simpledoc = simplexml_import_dom($doc);

        $pagination = $simpledoc->xpath('//a[@class="pagination_link"][last()]'); // fetches the last (2) pagination links from the page.
        $pagination = $pagination[0];
        $href = $pagination['href'];
        $last = (string)$pagination;

        $template = str_replace($last, '{page}', $href);
        $indices = array();
        for($i=1; $i<=(int)$last; $i++){
            $indices[] = strtr($template, array('{page}'=> $i));
        }

        return $indices;
    }

    /**
     * @param $html
     * @return string JSON string containing an array of the event listings.
     */
    public function scrapeListings($html){

        $doc = new \DOMDocument();
        $doc->loadHTML($html);

        $simpledoc = simplexml_import_dom($doc);

        // Set some defaults.
        $listings = array();

        foreach($simpledoc->xpath('//*[contains(@class,"ListingWhite")]') as $listing){

            $item = array(
                'artist' => null,
                'city' => null,
                'venue' => null,
                'date' => null,
                'price' => null,
            );

            $h3 = $listing->xpath('.//h3/a');
            $item['artist'] = (string)$h3[0];

            $citySpan = $listing->xpath('.//child::span[@class="venuetown"]');
            $item['city'] = strtoupper(trim((string)$citySpan[0],' :'));

            $venueSpan = $listing->xpath('.//child::span[@class="venuename"]');
            $item['venue'] = (string)$venueSpan[0];

            $dateP = $listing->xpath('.//child::blockquote/p');
            $item['date'] = (string)$dateP[0];

            if($price = $listing->xpath('.//*[@class="searchResultsPrice"]/strong')){
                $item['price'] = (string)$price[0];
            }

            // Appened the new listing item.
            $listings[] = $item;
        }

        // Return JSON struct
        return $this->formatter->format($listings);
    }

}