# Ytil

### PHP常用工具包(网络调用、参数校验)

#### 安装方法
```
composer require smileyi/utils
```

#### 基础配置
```
use SmileYi\Utils\Config;

Config::set([
    'log' => [
        // 日志存储路径
        'dir' => dirname(__FILE__) . '/../extra/log/',
    ],
    'common' => [
        //加密盐值
        'salt' => '4ckNt8GrgvqXYg1u',
    ],
    'base64' => [
        // 编码表
        // 'map' => 'OBrsYZabgQRSTUtu3JnoPDChijklWApqKLM6Evw7Ncde45mxGHIfXyz012FV89+/',
        'map' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/',
    ],
    'upload' => [
        // 上传目录
        'dir' => dirname(__FILE__) . '/../extra/upload/',
        // 允许的文件类型
        'exts' => ['jpg', 'png', 'jpeg'], // 不限制: ['*']
        // 文件大小限制
        'size' => 1024 * 1024 * 2,
    ],
])
```

#### 工具列表
-  网络调用
-  参数验证
-  Base64编码
-  加密解密
-  文件上传
-  日志写入

#### 具体用法
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
    echo "Errno:" . $e->getCode() . " Error:" . $e->getMessage()."\n";
}
```

##### 日志写入
```
use SmileYi\Utils\Log;

Log::getInstance()->put('user', ['nickname' => 'smileyi', 'sex' => 1]);
```

##### 常用工具
```
use SmileYi\Utils\Common;

# 加密解密
$t = 'hahaha';
$dt = Common::encrypt($t);
$et = Common::decrypt($dt);
echo "secret text:" . $dt . "\n";
echo "text:" . $et . "\n\n";

# 耗时计算
Common::exeTime('_start');
sleep(0.1);
Common::exeTime('_end');
echo "Exe time is:" . Common::exeTime('_start', '_end') . "s\n\n";

# 获取随机长度字符串
echo "random string is:" . Common::randStr(10) . "\n\n";

# 获取客户端IP
echo "client ip is:" . Common::getClientIp() . "\n\n";
```

##### Base64编码解码
```
use SmileYi\Utils\Base64;

$text = 'smileyi';

# 编码
$dt = Base64::encode($text);
# 解码
$et = Base64::decode($dt);

echo "text base64 encode is:" . $dt . "\n";
echo "text is:" . $et . "\n\n";
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
