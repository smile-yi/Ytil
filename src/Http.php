<?php
/**
 * Http.php
 * 网络请求类
 * 
 * @author  王中艺 <wangzy_smile@qq.com>
 * @date    2018-08-11 12:34:45
 */

namespace SmileYi\Ytil;

class Http {

    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';

    /**
     * get请求
     * @param   $url 
     * @param   $header 
     * @param   $proxy [<description>]
     * @param   $cert 证书信息
     * @return  response [<description>]
     */
    static function get(string $url, array $header = null, string $proxy = null, array $cert = null){
        //计时
        Common::exeTime('get_start');
        
        //发起请求
        $curl = self::_createCurl(self::METHOD_GET, $url, false, $header, $proxy, $cert);
        $res = self::_doCurl($curl);

        Common::exeTime('get_end');

        //记录日志
        Log::getInstance('file')->put('util_http_get', [
            'url' => $url,
            'cost' => Common::exeTime('Http_get_start', 'Http_get_end'),
            'header' => $header,
            'proxy' => $proxy,
            'cert' => $cert
        ]);
        
        return $res;
    }

    /**
     * post请求
     * @param   $url 
     * @param   $body 
     * @param   $header 
     * @param   $proxy 
     * @param   $cert 
     * @return  response [<description>]
     */
    static function post(string $url, $body = '', array $header = null, string $proxy = null, array $cert = null){
        //计时
        Common::exeTime('post_start');

        $curl = self::_createCurl(self::METHOD_POST, $url, $body, $header, $proxy, $cert);
        $res = self::_doCurl($curl);

        Common::exeTime('post_end');

        //记录日志
        Log::getInstance('file')->put('util_http_post', [
            'url' => $url,
            'cost' => Common::exeTime('post_start', 'post_end'),
            'body' => $body,
            'header' => $header,
            'proxy' => $proxy,
            'cert' => $cert
        ]);

        return $res;
    }

    /**
     * 并行调用
     * @param   $https 
    // $https = [
    //     'api1' => [
    //         'method' => Http::METHOD_GET,
    //         'url' => 'http://yi.dev/api.php?name=api1'
    //     ],
    //     'api2' => [
    //         'method' => Http::METHOD_POST,
    //         'url' => 'http://yi.dev/api.php?name=api2',
    //         'body' => [
    //             'name' => 'wangzhongyi'
    //         ]
    //     ],
    //     'api3' => [
    //         'method' => Http::METHOD_POST,
    //         'url' => 'http://yi.dev/api.php?name=api3',
    //         'header' => [
    //             'Content-type' => 'application/json'
    //         ],
    //         'body' => [
    //             'name' => 'wangzhongyi'
    //         ]
    //     ],
    // ];
     * @return  result [<description>]
     */
    static function multi($https, array $header = null, string $proxy = null, array $cert = null){
        //计时
        Common::exeTime('multi_start');

        $mh = curl_multi_init();
        $chs = [];
        foreach($https as $key => $http){
            $chs[$key] = self::_createCurl(
                $http['method'],
                $http['url'],
                $http['body'] ?? '',
                $http['header'] ?? $header,
                $http['proxy'] ?? $proxy,
                $http['cert'] ?? $cert
            );
            curl_multi_add_handle($mh, $chs[$key]);
        }

        $result = [];
        do {
            curl_multi_exec($mh, $active);
        } while ($active > 0);

        $result = [];
        foreach($chs as $key => $ch){
            $res = curl_multi_getcontent($ch);
            $result[$key] = self::_dealRes($res);
            curl_multi_remove_handle($mh, $ch);
        }
        curl_multi_close($mh);

        Common::exeTime('multi_end');

        Log::getInstance('file')->put('util_http_multi', [
            'https' => $https,
            'cost' => Common::exeTime('multi_start', 'multi_end')
        ]);

        return $result;
    }

    /**
     * 构造请求句柄
     * @param   $method
     * @param   $url 
     * @param   $body 
     * @param   $header  例：['Content-type' => 'application/json']
     * @param   $proxy
     * @param   $cert 
     * @return  ch [<description>]
     */
    static private function _createCurl($method, $url, $body = '', $header = null, $proxy = null, $cert = null){
        $curl = curl_init();

        //header 处理
        if($header){
            $headerRaw = [];
            foreach($header as $key => $val){
                $headerRaw[] = $key . ':' . $val;
            }
        }

        //body 处理 json自动转换
        if(isset($header['Content-type']) 
            && $header['Content-type'] == 'application/json' 
            && is_array($body)){
            $body = json_encode($body);
        }

        $opts = [
            CURLOPT_URL => $url, 
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_AUTOREFERER => true,
            CURLOPT_CUSTOMREQUEST => $method
        ];

        //post方法处理
        if(self::METHOD_POST == $method){
            $opts[CURLOPT_POSTFIELDS] = $body;
        }
        
        if($header){
            $opts[CURLOPT_HTTPHEADER] = $headerRaw;
        }
        if($proxy){
            $opts[CURLOPT_PROXY] = $proxy;
        }
        if($cert){
            $opts[CURLOPT_SSLCERT] = $cert['ssl_cert'];
            $opts[CURLOPT_SSLKEY] = $cert['ssl_key'];
            $opts[CURLOPT_CAINFO] = $cert['ca_info'];
        }

        curl_setopt_array($curl, $opts);

        return $curl;
    }

    /**
     * 执行请求
     * @param   $curl 
     * @return  $response [<description>]
     */
    static private function _doCurl($curl){
        $res = curl_exec($curl);
        if(curl_errno($curl)){
            throw new Exception(
                Exception::HTTP_CURL_FAIL, 
                curl_error($curl).'['.curl_errno($curl).']'
            );
        }
        curl_close($curl);

        return self::_dealRes($res);
    }

    /**
     * 处理响应内容
     * @param   $response 
     * @return  $response [<description>]
     */
    static private function _dealRes($res){
        //json自动转换
        if(Format::isJson($res)){
            return json_decode($res, true);
        }else{
            return $res;
        }
    }
} 