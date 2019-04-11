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

    const HTTP_CURL_FAIL = 5000;

    const UPLOAD_FILE_EMPTY = 5010;
    const UPLOAD_FILE_EXT_FORBID = 5011;
    const UPLOAD_FILE_SIZE_OVER = 5012;
    const UPLOAD_DIR_NULL = 5013;

    const MAP_CODE  = [
        self::HTTP_CURL_FAIL => 'Curl请求失败',
        self::UPLOAD_FILE_EMPTY => '上传文件为空',
        self::UPLOAD_FILE_EXT_FORBID => '上传文件后缀非法',
        self::UPLOAD_FILE_SIZE_OVER => '上传文件过大',
        self::UPLOAD_DIR_NULL => '上传目录不存在',
    ];

    function __construct($code, $remark = ''){
        $message    = isset(self::MAP_CODE[$code]) ? self::MAP_CODE[$code] : '未知错误';
        if($remark){
            $message    = $message.'['.$remark.']';
        }

        parent::__construct($message, $code);
    }

}
