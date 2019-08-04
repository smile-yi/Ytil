<?php

require_once '../vendor/autoload.php';

use SmileYi\Ytil\Upload;
use SmileYi\Ytil\Common;
use SmileYi\Ytil\Exception;

?>

<?php if (empty($_FILES)) { ?>

<form action="upload.php" method='post' enctype='multipart/form-data'>
    <input type='file' name='filename'>
    <button>提交</button>
</form>

<?php } else { 
    try {
        $upload = new Upload();
        $result = $upload->upload();
        Common::dump($result);
    } catch (Exception $e){
        echo "Errno:" . $e->getCode() . " Error:" . $e->getMessage() . "\n";
    }
} 
?>