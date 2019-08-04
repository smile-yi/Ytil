<?php

require_once '../vendor/autoload.php';

use SmileYi\Ytil\Http;
use SmileYi\Ytil\Exception;

$url = 'http://192.144.150.100/api.php?method=get&task_id=1001';
$header = [
    'Content-type' => 'application/json',
    'User-agent' => 'utils/http'
];
$body = [
    'param1' => 'value1'
];

//代理配置
//$proxy = 'http://xxx.xxx.xxx.xxx:8080'
$proxy = null;

//证书配置
// $cert = [
//     'ssl_cert' => 'path',
//     'ssl_key' => 'path',
//     'ca_info' => 'path'
// ];
$cert = null;

try {
    //GET调用
    $result = Http::get($url, $header, $proxy, $cert);
    var_dump($result);

    //POST调用
    $result = Http::post($url, $body, $header, $proxy, $cert);
    var_dump($result);

    //并行调用
    $multi = [];
    $multi['name1'] = [
        'method' => Http::METHOD_GET,
        'url' => $url,
        'header' => $header
    ];
    $multi['name2'] = [
        'method' => Http::METHOD_POST,
        'url' => $url,
        'header' => $header,
        'body' => $body
    ];

    $result = Http::multi($multi);
    var_dump($result);
}catch(Exception $e){
    echo "Errno:" . $e->getCode . " Error:" . $e->getMessage()."\n";
}
    
    

    