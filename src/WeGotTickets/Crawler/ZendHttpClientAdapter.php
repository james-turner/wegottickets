<?php

namespace WeGotTickets\Crawler;

use WeGotTickets\Crawler\Crawler;

class ZendHttpClientAdapter implements Crawler {

    /**
     * @var Zend_Http_Client
     */
    private $client;

    public function __construct(\Zend_Http_Client $client){
        $this->client = $client;
    }

    public function crawl($uri){

        $this->client->setUri($uri);
        $response = $this->client->request();
        return $response->getBody();

    }

}