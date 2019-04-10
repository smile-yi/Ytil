# PHPUtils

### PHP常用工具包(网络调用、参数校验)

#### 安装方法
```
composer require smileyi/utils
```

#### 工具列表
##### Http调用
```
use SmileYi\Utils\Http;
use SmileYi\Utils\Exception;

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
```

##### 参数校验
```
use SmileYi\Utils\Format;

# 邮箱
$bool = Format::isEmail('haha@gmail.com');

# 手机号
$bool = Format::isMobile('188xxxxxxxx');

# url
$bool = Format::isUrl('http://www.github.com/');

# 身份证号
$bool = Format::isIdCard('1775443288743221234');

# 整数
$bool = Format::isInteger(1001);

# json
$bool = Format::isJson('{"name":"smileyi"}');

# 用户名(6-16位数字+字母+_)
$bool = Format::isUsername('smileyi');

# 密码(6-20位数字+字母+_)
$bool = Format::isPassword('haha1234');
```
