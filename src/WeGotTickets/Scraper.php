<?php

namespace WeGotTickets;

class Scraper {

    public function __construct(){
        // Use internal errors.
        libxml_use_internal_errors(true);
    }

    public function scrapeIndices($html){

        $doc = new \DOMDocument();
        $doc->loadHTML($html);

        $simpledoc = simplexml_import_dom($doc);

//        foreach($simpledoc->xpath('') as $index){
//
//        }

        return array();
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
        return json_encode($listings);
    }

}