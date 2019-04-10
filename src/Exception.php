<?php
/**
 * Exception.php
 * 异常类
 * 
 * @author  王中艺 <wangzy_smile@qq.com>
 * @date    2019-04-10 16:55:47
 */

namespace SmileYi\Utils;

class Exception extends \Exception {

    const HTTP_CURL_FAIL = 5001;

    const MAP_CODE  = [
        self::HTTP_CURL_FAIL => 'Curl请求失败',
    ];

    function __construct($code, $remark = ''){
        $message    = isset(self::MAP_CODE[$code]) ? self::MAP_CODE[$code] : '未知错误';
        if($remark){
            $message    = $message.'['.$remark.']';
        }

        parent::__construct($message, $code);
    }

}
