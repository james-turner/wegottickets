<?php

// set up the include path properly.
set_include_path(implode(PATH_SEPARATOR, array(
  get_include_path(),
  realpath(dirname(__FILE__) . '/../src'),
  realpath(dirname(__FILE__) . '/../vendor')
)));

// require and register the Autoloader so we can get going.
require_once('WeGotTickets/Autoloader.php');
require_once('Zend/Loader/Autoloader.php');

use WeGotTickets\App;
use WeGotTickets\Autoloader;

// register WeGotTickets autoloader, and Zend's autoloader
Autoloader::register();
Zend_Loader_Autoloader::getInstance();

// RUN!
App::run($argv);