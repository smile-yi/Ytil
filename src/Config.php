<?php
/**
 * Config.php
 * 配置文件
 * 
 * @author  王中艺 <wangzy_smile@qq.com>
 * @date    2019-04-10 16:28:01
 */

namespace SmileYi\Utils;

class Config {

    static $config = [];

    /**
     * 设置配置
     * @param   $config
     * @return  boolean [<description>]
     */
    static function set($config) {
        self::$config = $config;
    }

    /**
     * 获取配置
     * @param   $keys
     * @return  $val [<description>]
     */
    static function get($keys){
        if (!$keys) {
            return self::$config;
        } else {
            $keys = explode('.', $keys);
            $config = self::$config;
            foreach ($keys as $key) {
                if (isset($config[$key])) {
                    $config = $config[$key];
                } else {
                    return null;
                }
            }

            return $config;
        }
    }
}

// 设置默认配置
Config::set([
    'log' => [
        'dir' => dirname(__FILE__) . '/../extra/log/',
    ],
    'common' => [
        'salt' => '4ckNt8GrgvqXYg1u',
    ],
    'base64' => [
        // 编码表
        // 'map' => 'OBrsYZabgQRSTUtu3JnoPDChijklWApqKLM6Evw7Ncde45mxGHIfXyz012FV89+/',
        'map' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/',
    ],
    'upload' => [
        'dir' => dirname(__FILE__) . '/../extra/upload/',
        'exts' => ['jpg', 'png', 'jpeg'], // 不限制: ['*']
        'size' => 1024 * 1024 * 2,
    ],
]);
