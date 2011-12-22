<?php

use WeGotTickets\Spider;

class SpiderTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var WeGotTickets\Spider
     */
    private $spider;

    private $mockClient;

    public function setUp(){
        $this->mockClient = $this->getMock('Zend_Http_Client', array(), array(), '', false); // don't call original clone!

        $this->spider = new Spider($this->mockClient);
    }

    /**
     * Spawn multiple connections to the
     *
     * @experimental
     * @test
     */
    public function squishyLoadTest(){

        // uri + path
        $uri = 'http://uat-shop.mcs.bskyb.com';
        $search = '/mcs-shop/lead/{postcode}/{dpsKey}';
        $get = '/mcs-shop/lead/{personId}';


        // get data
        $data = $this->SkyIQTestData();

        // init
        $multi = curl_multi_init();
        $sub_curls = array();

        foreach($data as $input){
            $url = $uri . $search;
            $postcode = strtoupper(str_replace(' ', '', $input['postcode']));
            $dpsKey = strtoupper(str_replace(' ', '', $input['dpsKey']));
            $url = str_replace('{postcode}', $postcode, $url);
            $url = str_replace('{dpsKey}', $dpsKey, $url);

            $sub_curls[] = $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, 0);

            curl_multi_add_handle($multi, $ch);
        }

        do {
            $mrc = curl_multi_exec($multi, $active);
        } while($mrc == CURLM_CALL_MULTI_PERFORM);

        while($active && $mrc == CURLM_OK){
            if(curl_multi_select($multi) != -1){
                do {
                    $mrc = curl_multi_exec($multi, $active);
                } while ($mrc == CURLM_CALL_MULTI_PERFORM);
            }
        }

        $personIds = array();
        // for each curl handle fetch the content and close it.
        foreach($sub_curls as $ch){

            $json = json_decode(curl_multi_getcontent($ch), true);
            foreach($json as $person){
                $personIds[] = $person['personId'];
            }

            curl_multi_remove_handle($multi, $ch);
        }

        var_dump($personIds);

        // close off
        curl_multi_close($multi);

        // Do same again for get lead... SPANK IT!
        $multi = curl_multi_init();
        $sub_curls = array();
        foreach($personIds as $personId){
            $url = $uri . $get;
            $url = str_replace('{personId}', $personId, $url);

            $sub_curls[] = $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, 0);

            curl_multi_add_handle($multi, $ch);
        }

        do {
            $mrc = curl_multi_exec($multi, $active);
        } while($mrc == CURLM_CALL_MULTI_PERFORM);

        while($active && $mrc == CURLM_OK){
            if(curl_multi_select($multi) != -1){
                do {
                    $mrc = curl_multi_exec($multi, $active);
                } while ($mrc == CURLM_CALL_MULTI_PERFORM);
            }
        }

        $content = array();
        foreach($sub_curls as $ch){
            $content[] = curl_multi_getcontent($ch);
            curl_multi_remove_handle($multi, $ch);
        }

        var_dump($content);

        curl_multi_close($multi);
    }

}