<?php

class JSONFormatterTest extends PHPUnit_Framework_TestCase {

    private $formatter;

    public function setUp(){
        $this->formatter = new \WeGotTickets\Formatter\JSONFormatter();
    }

    /**
     * @test
     */
    public function formatReturnsValidJsonWhenGivenFormattableItem(){

        $this->assertNotEquals(null, json_decode($this->formatter->format("string")));

    }

}