<?php

namespace WeGotTickets;

class Autoloader {

    private static $isRegistered = false;

    static public function register(){
        // only happens once.
        if(false === self::$isRegistered){
            spl_autoload_register(function($class){
                if (0 === strpos($class, 'WeGotTickets\\')) {
                     $class = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
                     require_once $class;
                }
               return false;
            });
        }
    }

}