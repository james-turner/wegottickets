<?php

use WeGotTickets\Scraper;

class ScraperTest extends PHPUnit_Framework_TestCase {

    /**
     * @var WeGetTickets\Scraper
     */
    private $scraper;

    /**
     * HTML fixture
     * @var string
     */
    private $fixture;

    public function setUp(){

        $this->scraper = new Scraper();
        $this->fixture = FixtureLoader::load('listings');
    }

    /**
     * @test
     */
    public function scrapeReturnsAJsonStringWhenProvidedWithAPageToScrape(){

        $result = $this->scraper->scrape($this->fixture);

        // json results return false if unparseable, so test to make sure it's not false.
        $this->assertNotEquals(false, json_decode($result));

    }

    /**
     * @test
     */
    public function scrapeReturnsAJsonObjectThatContainsTheCorrectKeys(){

        $json = $this->scraper->scrape($this->fixture);

        $result = json_decode($json, true);

        // we're expecting a few keys
        foreach(array('artist', 'city', 'venue', 'date', 'price') as $expectedKey){

            $this->assertArrayHasKey($expectedKey, $result);
        }
    }

    /**
     * @test
     */
    public function scrapeReturnsAJsonObjectThatContainsTheCorrectValues(){

        $json = $this->scraper->scrape($this->fixture);

        $result = json_decode($json, true);

        // we're expecting a few keys
        $this->assertEquals('99 CLUB LEICESTER SQUARE COMEDY', $result['artist']);
        $this->assertEquals('', $result['city']);
        $this->assertEquals('London 99 Club @ Storm Nightclub', $result['venue']);
        $this->assertEquals('Tue 20th Dec, 2011', $result['date']);
//        $this->assertEquals('99 CLUB LEICESTER SQUARE COMEDY', $result['price']);

    }

}