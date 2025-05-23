<?php


/**
 * ----------------------------------------------------------
 * Compiled Classes and Required Files are initiated here
 * ----------------------------------------------------------
 */

 require "app/start.php";

/**
 * ----------------------------------------------------------
 * Start Session
 * ----------------------------------------------------------
 */

Session::init();

/**
 * ----------------------------------------------------------
 * Autoload Vendor
 * ----------------------------------------------------------
 */

require 'vendor/autoload.php';

/**
 * ----------------------------------------------------------
 * Compiled Classes and Required Files are initiated here
 * ----------------------------------------------------------
 */


/**
 * ----------------------------------------------------------
 * Create Instance of the Application
 * ----------------------------------------------------------
 */

$app = new App;

/**
 * ----------------------------------------------------------
 * Run the Application
 * ----------------------------------------------------------
 */

$app->run();