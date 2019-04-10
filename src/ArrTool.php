<?php
/**
 * ArrTool.php
 * 数组处理工具
 * 
 * @author  wangzhongyi <wangzy_smile@qq.com>
 * @date    2017-10-12
 **/

namespace SmileYi\Utils;

class ArrTool {

    /**
     * 过滤数组
     * @param   $array 数组
     * @param   $columns 字段
     * @return  array [<description>]
     */
    static function leach(array $array, array $columns, $withNone = false, $default = null){
        $result     = [];
        if(is_array(reset($array))){
            foreach($array as $key => $val){
                $result[$key]   = self::_leachAction($val, $columns, $withNone, $default);
            }
        }else{
            $result     = self::_leachAction($array, $columns, $withNone, $default);
        }

        return $result;
    }

    static private function _leachAction(array $array, array $columns, $withNone, $default){
        $result     = [];
        foreach($array as $key => $val){
            foreach($columns as $column){
                if(isset($array[$column]) && $array[$column] !== ''){
                    $result[$column]    = $array[$column];
                }else{
                    if($withNone){
                        $result[$column]    = $default;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * 将数组转为文本
     * @param   $array
     * @return  $string
     */
    static function toText($array, $level = 0){
        $string = '';
        $tab = "    ";
        foreach($array as $key => $val){
            if(!is_array($val)){
                $string .= Common::multString($tab, $level).$key.": ".$val."\n";
            }else{
                $string .= Common::multString($tab, $level).$key.": \n".
                    self::toText($val, $level + 1);
            }
        }
        return $string;
    }
}
