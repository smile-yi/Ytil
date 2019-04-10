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
    static function get(){
        $args = func_get_args();
        $config = self::$config;
        foreach($args as $arg){
            if(isset($config[$arg])){
                $config = $config[$arg];
            }else{
                return null;
            }
        }
        return $config;
    }
}

// 设置默认配置
Config::set([
    'log' => [
        'dir' => dirname(__FILE__) . '/../log/',
    ]
]);
