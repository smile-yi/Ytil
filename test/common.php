<?php
require_once '../vendor/autoload.php';

use SmileYi\Utils\Common;

header('Content-type:text/plain');

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

# 获取客户端header
echo "client header is: \n";
var_dump(Common::getClientHeader('User-Agent'));  