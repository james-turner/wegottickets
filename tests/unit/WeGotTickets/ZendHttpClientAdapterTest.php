<?php

use WeGotTickets\Crawler\ZendHttpClientAdapter;

class ZendHttpClientAdapterTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var WeGotTickets\Spider
     */
    private $spider;

    private $mockClient;

    public function setUp(){
        $this->mockClient = $this->getMock('Zend_Http_Client', array(), array(), '', false); // don't call original clone!

        $this->spider = new ZendHttpClientAdapter($this->mockClient);
    }

    /**
     * @test
     */
    public function crawlReturnsTheClientResponseAndInvokesAndExecutesZendClientCorrectly(){

        $html = FixtureLoader::load('listings');
        $uri = 'http://localhost';

        $mockResponse = $this->getMock('Zend_Http_Response', array(), array(), '', false);


        $this->mockClient->expects($this->once())
                         ->method('setUri')
                         ->with($uri);

        $this->mockClient->expects($this->once())
                         ->method('request')
                         ->will($this->returnValue($mockResponse));

        $mockResponse->expects($this->once())
                         ->method('getBody')
                         ->will($this->returnValue($html));

        $this->assertEquals($html, $this->spider->crawl($uri));

    }
}