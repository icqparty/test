<?php

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

$container['db'] = function ($container) {

    $cfg = $container->get('settings')['db'];
    return new PDO("mysql:host={$cfg['host']};dbname={$cfg['dbname']}", $cfg['user'], $cfg['password']);

};
$container['parser'] = function ($container) {
    $options = $container->get('settings')['parser'];

    return new \Parser\Parser($options);;
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], Monolog\Logger::DEBUG));
    return $logger;
};
