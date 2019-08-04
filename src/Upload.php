<?php
/**
 * Upload.php
 * 文件上传类
 * 
 * @author  王中艺 <wangzy_smile@qq.com>
 * @date    2017-09-12
 */

// 调用方式
// $upload = new Upload();
// $info = $upload->upload();

namespace SmileYi\Ytil;

class Upload {

    private $config     = [];

    function __construct($config = []){
        $this->config = array_merge(Config::get('upload'), $config);
        if (!file_exists($this->config['dir'])) {
            throw new Exception(Exception::UPLOAD_DIR_NULL);
        }
    }

    /**
     * 批量执行上传
     * @param   $zip 是否压缩
     * @return  $info
     */
    function upload(){
        if(empty($_FILES)){
            throw new Exception(Exception::UPLOAD_FILE_EMPTY);
        }

        //批量处理文件
        $info   = [];
        foreach($_FILES as $key => $file){
            $info[$key] = $this->uploadOne($file, $zip);
        }

        return $info;
    }

    /**
     * 单个文件上传
     * @param   $file
     * @param   $zip    是否压缩
     * @return  $info
     */
    function uploadOne($file){
        if(!$file['tmp_name']){
            throw new Exception(Exception::UPLOAD_FILE_EMPTY);
        }

        $info   = $this->getFileInfo($file);
        if (!in_array('*', $this->config['exts']) 
            && !in_array(strtolower($info['ext']), $this->config['exts'])) {
            throw new Exception(Exception::UPLOAD_FILE_EXT_FORBID);
        }
        if ($info['size'] > $this->config['size']) {
            throw new Exception(Exception::UPLOAD_FILE_SIZE_OVER);
        }
        if(!file_exists($info['dir'])){
            mkdir($info['dir'], 0775);
        }

        //移动文件
        move_uploaded_file($info['tmp_name'], $info['path']);

        return $info;
    }

    /**
     * 文件信息追加
     * @param   $file
     * @return  $info
     */
    private function getFileInfo($file){
        $info   = $file;
        $info['ext']    = @end(explode('.', $file['name']));
        $info['path']   = implode('/', [
            $this->config['dir'], 
            date('Y-m-d'), 
            md5($file['tmp_name']) . '.' . $info['ext']
        ]);
        $info['dir']   = dirname($info['path']);
        return $info;
    }  
}
