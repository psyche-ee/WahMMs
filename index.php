<?php

// Serve static files directly when using PHP's built-in server
if (php_sapi_name() === 'cli-server') {
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . '/public' . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

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

if (empty($_SESSION['test'])) {
    $_SESSION['test'] = rand(1000, 9999);
    error_log('Set test session: ' . $_SESSION['test']);
} else {
    error_log('Read test session: ' . $_SESSION['test']);
}

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