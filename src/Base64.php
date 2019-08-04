<?php
/**
 * Base64.php
 * base64编码
 * 
 * @author  王中艺 <wangzy_smile@qq.com>
 * @date    2019-01-07 20:30:16
 */

namespace SmileYi\Ytil;

class Base64 {

    /**
     * 编码 
     * @param string $string 
     * @return string $result
     */
    static function encode($string){
        $result = '';
        $len = strlen($string);
        $num = 0;
        $bin = 0;
        $arr = array();
        if($len >= 3) {
            for($i=0;$i<$len;$i++) {
                $bin = $bin << 8;
                $bin = $bin | ord($string{$i});
                if(($i+1)%3 == 0) {
                    $result .= self::encodeFunc($bin,3);
                    $bin = 0;
                }
            }
        }
        if($len%3 == 1) {
            $bin = ord($string[$len-1]);
            $bin = $bin << 4;
            $result .= self::encodeFunc($bin,1);
            $result .= '==';
        } else if($len%3 == 2) {
            $bin = ord($string[$len-2]);
            $bin = $bin << 8;
            $bin = $bin | ord($string[$len-1]);
            $bin = $bin << 2;
            $result .= self::encodeFunc($bin,2);
            $result .= '=';
        }
        return $result;
    }

    /**
     * 解码
     * @param string $string 
     * @return string string
     */
    static function decode($str){
        $mapStr = array_flip(str_split(Config::get('base64.map')));
        $bin = 0;
        $length = strlen($str)-1;
        $_decode_result = '';
        $len = 0;
        $i = 0;
        while( ($len <= $length) ) {
            $ch = $str[$len++];
            if ($ch == '=') {
                if (isset($str[$len]) && $str[$len] != '=' && (($i % 4) == 1)) {
                    return NULL;
                }
                continue;
            }
            
            $ch = $mapStr[$ch];
            if ($ch < 0 || $ch == -1) {
                continue;
            } else if ($ch == -2) {
                return NULL;
            }
            switch($i % 4) {
                case 0:
                    $bin = intval($ch) << 2;
                    break;
                case 1:
                    $bin = intval($bin) | intval($ch) >> 4;
                    $_decode_result .= chr($bin);
                    $bin = ( intval($ch) & 0x0f ) << 4;
                    break;
                case 2:
                    $bin = intval($bin) | intval($ch) >> 2;
                    $_decode_result .= chr($bin);
                    $bin = ( intval($ch) & 0x03 ) << 6;
                    break;
                case 3:
                    $bin = intval($bin) | intval($ch);
                    $_decode_result .= chr($bin);
                    break;
            }
            $i++;
        }
        return $_decode_result;
    }

    static private function encodeFunc($bin,$bytes = 3) {
        $map = Config::get('base64.map');
        $encodeData = 0xfc0000;
        $ret = '';
        $num = 3;
        $matches = 0;
        $bits1 = ($num - $bytes) * 6;
        $bits2 = $bytes * 6;
        $matches = $encodeData >> $bits1;
        while( $matches ) {
            $result = $bin & $matches;
            $result = $result >> $bits2;
            $bytes--;
            $bits1 = ($num - $bytes) * 6;
            $bits2 = $bytes * 6;
            $matches = $encodeData >> $bits1;
            $ret .= $map[$result];
        }

        return $ret;
    }
}
