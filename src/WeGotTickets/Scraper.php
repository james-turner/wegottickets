<?php

namespace WeGotTickets;

class Scraper {

    private $listingClass = 'ListingWhite';
    private $listingPrices = 'ListingPrices';
    private $listingAct = 'ListingAct';


    public function __construct(){
        // Use internal errors.
        libxml_use_internal_errors(true);
    }

    public function scrape($html){

        $doc = new \DOMDocument();
        $doc->loadHTML($html);

        $simpledoc = simplexml_import_dom($doc);

        // Set some defaults.
        $artist = null;
        $city = null;
        $venue = null;
        $date = null;
        $price = null;

        foreach($simpledoc->xpath('//*[contains(@class,"ListingWhite")]') as $listing){

            $h3 = $listing->xpath('.//h3/a');
            $artist = (string)$h3[0];

            $citySpan = $listing->xpath('.//child::span[@class="venuetown"]');
            $city = strtoupper(trim((string)$citySpan[0],' :'));

            $venueSpan = $listing->xpath('.//child::span[@class="venuename"]');
            $venue = (string)$venueSpan[0];

            $dateP = $listing->xpath('.//child::blockquote/p');
            $date = (string)$dateP[0];

            if($price = $listing->xpath('.//*[@class="searchResultsPrice"]/strong')){
                $price = (string)$price[0];
            }

            break;
        }

        // Return JSON struct
        return json_encode(array(
            'artist' => $artist,
            'city' => $city,
            'venue' => $venue,
            'date' => $date,
            'price' => $price,
        ));
    }

}