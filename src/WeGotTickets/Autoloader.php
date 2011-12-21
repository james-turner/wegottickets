<?php

namespace WeGotTickets;

class Autoloader {

    private static $isRegistered = false;

    static public function register(){
        // only happens once.
        if(false === self::$isRegistered){
            spl_autoload_register(function($class){

                if (0 === strpos($class, 'WeGotTickets\\')) {
                    if ('\\' != DIRECTORY_SEPARATOR) {
                        $class = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
                    } else {
                        $class = $class . '.php';
                    }
                    require_once $class;
               }
               return false;
            });
        }
    }

}