<?php
return array(
    'settings' => array(
        'displayErrorDetails' => true,

        // Renderer settings
        'renderer' => array(
            'template_path' => __DIR__ . '/../templates/',
        ),

        'db' => array(
            'host' => 'leadroi.ru' ,
            'dbname'=>'parser_email_leadroi',
            'user'=>'leadroi',
            'password'=>'wcvM)_{9435w)(N*)(',
        ),

        'parser' => array(
            'category' => array(
                'title' => 'По категориям',
                'port' => 9050,
                'host' => 'localhost',
                'status' => 'stop',
            ),
            'all' => array(
                'title' => 'Все',
                'port' => 9051,
                'host' => 'localhost',
                'status' => 'stop',
            ),
            'search' => array(
                'title' => 'По ключевому слову',
                'port' => 9052,
                'host' => 'localhost',
                'status' => 'stop',
            ),

        ),

        // Monolog settings
        'logger' => array(
            'name' => 'slim-app',
            'path' => __DIR__ . '/var/www/test/data/app.log',
        ),
    ),
);
