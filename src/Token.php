<?php
/**
 * Token.php
 * 令牌工具
 * 
 * @author  王中艺 <wangzy_smile@qq.com>
 * @date    2019-05-16 17:37:05
 */

namespace SmileYi\Utils;

class Token {

    /**
     * 令牌生成
     * @param   $byIp 是否依据ip
     * @param   $byUa 是否依据Ua
     * @return  token [<description>]
     */
    static function create($byIp = true, $byUa = true) {
        $token = Common::md5(time() . rand(10000, 99999));

        $prefix = 'prefix_';
        if ($byIp) {
            $prefix .= Common::getClientIp();
        }
        if ($byUa) {
            $prefix .= Common::getClientHeader('User-Agent');
        }
        $prefix = Common::md5($prefix);

        return substr($prefix, 0, 10) . substr($token, 10);
    }

    /**
     * 令牌验证
     * @param   $token 
     * @param   $byIp 是否依据ip
     * @param   $byUa 是否依据ua
     * @return  boolean [<description>]
     */
    static function verify($token, $byIp = true, $byUa = true) {
        if (strlen($token) != 32) {
            return false;
        }

        $prefix = 'prefix_';
        if ($byIp) {
            $prefix .= Common::getClientIp();
        }
        if ($byUa) {
            $prefix .= Common::getClientHeader('User-Agent');
        }
        $prefix = Common::md5($prefix);

        return substr($prefix, 0, 10) === substr($token, 0, 10);
    }
}