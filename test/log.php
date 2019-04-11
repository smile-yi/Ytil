<?php

require_once '../vendor/autoload.php';

use SmileYi\Utils\Log;

Log::getInstance()->put('user', [
    'nickname' => 'smileyi', 
    'sex' => 1,
    'hobby' => 'sleep',
]);

// 日志信息
// datetime: 2019-04-11 06:20:14
// nickname: smileyi
// sex: 1
// hobby: sleep
