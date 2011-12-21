<?php

class FileNotFoundException extends Exception {}

class FixtureLoader {

    private static $dir = '/../fixtures/';
    private static $ext = 'html';

    static public function load($fixture){
        $dir = dirname(__FILE__) . self::$dir;
        $filename = $dir . $fixture . '.' . self::$ext;

        if(file_exists($filename)){
            return file_get_contents($filename);
        } else {
            throw new FileNotFoundException();
        }

    }

}