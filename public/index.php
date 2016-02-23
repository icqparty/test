<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';


// Instantiate the app
$settings = require __DIR__ . '/../src/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '/../src/dependencies.php';
require __DIR__ . '/../src/routes.php';

//$f=new \Parser\RequestParser();
//echo $f->get('http://news.rambler.ru/');

// Run app
$app->run();
