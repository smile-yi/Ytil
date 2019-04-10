<?php
/**
 * Common.php
 * 常用工具类
 * 
 * @author  王中艺 <wangzy_smile@qq.com>
 * @date    2017-08-24
 */

namespace SmileYi\Utils;

class Common {

    /**
     * md5加密
     * @param   string  $string  [description]
     * @return  string           
     */
    static function md5($string){
        return md5($string . config('app.salt'));
    }

    /**
     * 获取客户端IP
     * @param   $isLong     是否为longint类型
     * @return  string or integer
     */
    static function getClientIp($isLong = false){
        $ip     = $_SERVER['REMOTE_ADDR'];
        return $isLong ? ip2long($ip) : $ip;
    }

    /**
     * 绑定图片信息
     * @param   $url
     * @return  $info
     */
    static function getImgInfo($url){
        $size   = getimagesize($url);
        $info   = [
            'url'   => $url,
            'width' => $size[0],
            'height'    => $size[1]
        ];

        return $info;
    }

    /**
     * 文本加密
     * @param   $data
     * @return  $string
     */
    static function encrypt($data){
        $key  = config('app.salt');
        $expire     = 0;
        $data = base64_encode($data);
        $x    = 0;
        $len  = strlen($data);
        $l    = strlen($key);
        $char = '';

        for ($i = 0; $i < $len; $i++) {
            if ($x == $l) $x = 0;
            $char .= substr($key, $x, 1);
            $x++;
        }

        $str = sprintf('%010d', $expire ? $expire + time():0);

        for ($i = 0; $i < $len; $i++) {
            $str .= chr(ord(substr($data, $i, 1)) + (ord(substr($char, $i, 1)))%256);
        }
        
        return str_replace(array('+','/','='),array('-','_',''),base64_encode($str));
    }

    /**
     * 文本解密
     * @param   $data
     * @return  string
     */
    static function decrypt($data){
        $key  = config('app.salt');
        $data   = str_replace(array('-','_'),array('+','/'),$data);
        $mod4   = strlen($data) % 4;
        if ($mod4) {
           $data .= substr('====', $mod4);
        }
        $data   = base64_decode($data);
        $expire = substr($data,0,10);
        $data   = substr($data,10);

        if($expire > 0 && $expire < time()) {
            return '';
        }
        $x      = 0;
        $len    = strlen($data);
        $l      = strlen($key);
        $char   = $str = '';

        for ($i = 0; $i < $len; $i++) {
            if ($x == $l) $x = 0;
            $char .= substr($key, $x, 1);
            $x++;
        }

        for ($i = 0; $i < $len; $i++) {
            if (ord(substr($data, $i, 1))<ord(substr($char, $i, 1))) {
                $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
            }else{
                $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
            }
        }

        return base64_decode($str);
    }

    /**
     * 随即字符串获取
     * @param   $length
     * @return  $string
     */
    static function randStr($length = 10){
        $loop       = ceil($length/32);
        $surplus    = 32 - $length%32;
        $string     = '';
        for($i = 0; $i < $loop; $i++){
            $string     .= md5(rand(1000,9999));
        }

        return strtoupper(substr($string, $surplus));
    }

    /**
     * 计算坐标距离
     * @param  [type] $lat1 [description]
     * @param  [type] $lng1 [description]
     * @param  [type] $lat2 [description]
     * @param  [type] $lng2 [description]
     * @return [type]       [description]
     */
    static function reckonDistance($lat1, $lng1, $lat2, $lng2){
        $pi     = 3.1415926535898;
        $earthRadius    = 6378.137;
        $radLat1 = $lat1 * ($pi / 180);
        $radLat2 = $lat2 * ($pi / 180);
        $a = $radLat1 - $radLat2;
        $b = ($lng1 * ($pi / 180)) - ($lng2 * ($pi / 180));
        $s = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)));
        $s = $s * $earthRadius;
        $s = round($s * 10000) / 10000;
        return round($s, 3);
    }

    /**
     * 浏览器友好的变量输出
     * @param mixed $var 变量
     * @param boolean $echo 是否输出 默认为True 如果为false 则返回输出字符串
     * @param string $label 标签 默认为空
     * @param boolean $strict 是否严谨 
     * @return void|string
     */
    static function dump($var, $echo=true, $label=null, $strict=true) {
        $label = ($label === null) ? '' : rtrim($label) . ' ';
        if (!$strict) {
            if (ini_get('html_errors')) {
                $output = print_r($var, true);
                $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
            } else {
                $output = $label . print_r($var, true);
            }
        } else {
            ob_start();
            var_dump($var);
            $output = ob_get_clean();
            if (!extension_loaded('xdebug')) {
                $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
                $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
            }
        }
        if ($echo) {
            echo($output);
            return null;
        }else
            return $output;
    }

    /**
     * 计算执行时间
     * @param   $tag
     * @param   $tag2
     * @return  boolean || time
     */
    static $executeTimeTag  = [];
    static function exeTime($tag, $tag2 = null){
        if($tag2 === null){
            //记录执行时间
            self::$executeTimeTag[$tag]     = microtime(true);
            return true;
        }else if(isset(self::$executeTimeTag[$tag]) && isset(self::$executeTimeTag[$tag2])){
            //返回执行时间
            return abs(round(self::$executeTimeTag[$tag2] - self::$executeTimeTag[$tag], 3));
        }else{
            return true;
        }
    }
    
    /**
     * 多倍字符串
     * @param   $string
     * @param   $num
     * @return  $string
     */
    static function multString($string, $num){
        $s = '';
        for($i = 0; $i < $num; $i++){
            $s .= $string;
        }
        return $s;
    }
}
