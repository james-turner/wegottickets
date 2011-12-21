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
require_once('helpers/FixtureLoader.php');

use WeGotTickets\Autoloader;

Autoloader::register();
Zend_Loader_Autoloader::getInstance();
