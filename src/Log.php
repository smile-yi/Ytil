<?php
/**
 * Log.php
 * 日志工具
 * 
 * @author  王中艺 <wangzy_smile@qq.com>
 * @date    2017-09-21
 */

namespace SmileYi\Ytil;

class Log {

    private static $instance;

    private $dir;

    //单例获取
    static function getInstance(){
        if(!isset(self::$instance)){
            self::$instance = new self();
        }

        return self::$instance;
    }

    //构造
    function __construct(){
        $this->dir = Config::get('log.dir') ?? dirname(__FILE__ . '/../log/');
    }

    /**
     * 日志写入
     * @param   $name 
     * @param   $content
     * @return  boolean [<description>]
     */
    function put($name, $content){
        $logFile = $this->dir . $name . '.' . date('Ymd') . '.log';

        //格式化日志
        $content = $this->formatContent($content);

        file_put_contents($logFile, $content, FILE_APPEND);

        return true;
    }

    /**
     * 格式化日志
     * @param   $content
     * @return  $content
     */
    function formatContent($content){
        is_string($content) && $content = ['content' => $content];
        $content = array_merge([
            'datetime' => date('Y-m-d H:i:s')
        ], $content);

        $content = json_decode(json_encode($content), true);
        $content = ArrTool::toText($content);

        return $content."\n";
    } 
}
