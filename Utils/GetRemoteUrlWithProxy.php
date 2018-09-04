<?php

namespace common\utils;

class  GetRemoteUrlWithProxy {

    protected  $ch;
    public function  __construct ($url) {

         $this->ch = curl_init($url);
         curl_setopt($this->ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:49.0) Gecko/20100101 Firefox/49.0');
         curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
         curl_setopt($this->ch, CURLOPT_HEADER, 0);
         curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
         curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 0);
         curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, 1);
             //通过代理访问需要额外添加的参数项
        curl_setopt($this->ch, CURLOPT_HTTPPROXYTUNNEL, 0);
        curl_setopt($this->ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5_HOSTNAME);
        curl_setopt($this->ch, CURLOPT_PROXY, "127.0.0.1");
        curl_setopt($this->ch, CURLOPT_PROXYPORT, "1080");
    }

    public function getRemoteSource() {

         $result = curl_exec($this->ch);
         if($result === false){
            return (curl_error($this->ch));
         }
         return (($result));
    }
    public function __destruct ()
    {
        curl_close($this->ch);
    }


}