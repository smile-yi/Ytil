<?php

require_once '../vendor/autoload.php';

use SmileYi\Ytil\Config;

$config = [
    'log' => [
        'dir' => '/var/log/php/'
    ],
    'common' => [
        'salt' => 'hahaha123456'
    ],
];

Config::set($config);

var_dump(Config::get('log.dir'));
var_dump(Config::get('common.salt'));