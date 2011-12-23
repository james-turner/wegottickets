<?php

use WeGotTickets\Parser;

class parserTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var WeGotTickets\parser
     */
    private $parser;

    /**
     * HTML fixture
     * @var string
     */
    private $fixture;

    public function setUp(){

        $this->parser = new Parser();
        $this->fixture = FixtureLoader::load('listings');
    }

    /**
     * @test
     */
    public function parseIndicesReturnsAnArray(){

        $this->assertThat($this->parser->parseIndices($this->fixture), $this->isType('array'));

    }

    /**
     * @test
     */
    public function parseIndicesGivesBackAllThePageUrls(){

        // based on fixture
        $expect = 530;

        $indices = $this->parser->parseIndices($this->fixture);

        $this->assertEquals($expect, count($indices));

    }


    /**
     * @test
     */
    public function parseListingsReturnsAnArrayProvidedWithAPageToparse(){

        $listings = $this->parser->parseListings($this->fixture);

        // json results return false if unparseable, so test to make sure it's not false.
        $this->assertThat($listings, $this->isType('array'));

    }

    /**
     * @test
     */
    public function parseListingsReturnsAnArrayThatContainsTheCorrectNumberOfListings(){

        $listings = $this->parser->parseListings($this->fixture);

        $this->assertEquals(10, count($listings));
    }

    /**
     * @test
     */
    public function parseListingsReturnsArraysThatContainTheCorrectKeys(){

        $listings = $this->parser->parseListings($this->fixture);

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
    public function parseListingsReturnsAnArrayThatContainsTheCorrectValues(){

        $listings = $this->parser->parseListings($this->fixture);

        $sample = $listings[0];


        // we're expecting a few keys
        $this->assertEquals('99 CLUB LEICESTER SQUARE COMEDY', $sample['artist']);
        $this->assertEquals('', $sample['city']); // some fields are indeterminate!
        $this->assertEquals('London 99 Club @ Storm Nightclub', $sample['venue']);
        $this->assertEquals('Tue 20th Dec, 2011', $sample['date']);
        $this->assertEquals('', $sample['price']); // some fields are indeterminate!

    }

}