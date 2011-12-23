<?php

use WeGotTickets\Scraper;

class ScraperTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var WeGotTickets\Scraper
     */
    private $scraper;

    /**
     * HTML fixture
     * @var string
     */
    private $fixture;

    public function setUp(){

        $this->scraper = new Scraper(new \WeGotTickets\Formatter\JSONFormatter());
        $this->fixture = FixtureLoader::load('listings');
    }

    /**
     * @test
     */
    public function scrapeIndicesReturnsAnArray(){

        $this->assertThat($this->scraper->scrapeIndices($this->fixture), $this->isType('array'));

    }

    /**
     * @test
     */
    public function scrapeIndicesGivesBackAllThePageUrls(){

        // based on fixture
        $expect = 530;

        $indices = $this->scraper->scrapeIndices($this->fixture);

        $this->assertEquals($expect, count($indices));

    }


    /**
     * @test
     */
    public function scrapeListingsReturnsAJsonStringWhenProvidedWithAPageToScrape(){

        $result = $this->scraper->scrapeListings($this->fixture);

        // json results return false if unparseable, so test to make sure it's not false.
        $this->assertNotEquals(false, json_decode($result));

    }

    /**
     * @test
     */
    public function scrapeListingsReturnsAJsonObjectThatContainsTheCorrectNumberOfListings(){

        $json = $this->scraper->scrapeListings($this->fixture);

        $listings = json_decode($json, true);

        $this->assertEquals(10, count($listings));
    }

    /**
     * @test
     */
    public function scrapeListingsReturnsJsonObjectsThatContainTheCorrectKeys(){

        $json = $this->scraper->scrapeListings($this->fixture);

        $listings = json_decode($json, true);

        // we're expecting a few keys
        foreach($listings as $listing){
            foreach(array('artist', 'city', 'venue', 'date', 'price') as $expectedKey){
                $this->assertArrayHasKey($expectedKey, $listing);
            }
        }
    }

    /**
     * @test
     */
    public function scrapeListingsReturnsAJsonObjectThatContainsTheCorrectValues(){

        $json = $this->scraper->scrapeListings($this->fixture);

        $listings = json_decode($json, true);

        $sample = $listings[0];


        // we're expecting a few keys
        $this->assertEquals('99 CLUB LEICESTER SQUARE COMEDY', $sample['artist']);
        $this->assertEquals('', $sample['city']); // some fields are indeterminate!
        $this->assertEquals('London 99 Club @ Storm Nightclub', $sample['venue']);
        $this->assertEquals('Tue 20th Dec, 2011', $sample['date']);
        $this->assertEquals('', $sample['price']); // some fields are indeterminate!

    }

}