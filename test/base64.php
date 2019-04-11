<?php

require_once '../vendor/autoload.php';

use SmileYi\Utils\Base64;

$text = 'smileyi';
$dt = Base64::encode($text);
$et = Base64::decode($dt);
echo "text base64 encode is:" . $dt . "\n";
echo "text is:" . $et . "\n\n";