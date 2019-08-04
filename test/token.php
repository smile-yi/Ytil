<?php
/**
 * token.php
 * token工具
 * 
 * @author  王中艺 <wangzy_smile@qq.com>
 * @date    2019-05-16 19:15:23
 */

require_once '../vendor/autoload.php';

use SmileYi\Ytil\Token;

$token = Token::create();
echo "token is: ".$token."<br>";
echo "token is right? " . (Token::verify($token) ? "Yes" : "No");
