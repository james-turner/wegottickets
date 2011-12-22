<?php

namespace WeGotTickets;

class Spider {

    /**
     * @var Zend_Http_Client
     */
    private $client;

    public function __construct(\Zend_Http_Client $client){
        $this->client = $client;
    }

    public function crawl($uri){

        $response = $this->client->request();
        return $response->getBody();

    }


}