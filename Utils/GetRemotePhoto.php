<?php
namespace common\utils;
class  GetRemotePhoto {


   public function downloadImage($url, $path)
   {

     $ch = curl_init();
     curl_setopt($ch, CURLOPT_URL, $url);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
     //通过代理访问需要额外添加的参数项
 	 curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 0);
 	 curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5_HOSTNAME);
 	 curl_setopt($ch, CURLOPT_PROXY, "127.0.0.1");
 	 curl_setopt($ch, CURLOPT_PROXYPORT, "1080");
     $file = curl_exec($ch);
     curl_close($ch);
     return $this->saveAsImage($url, $file, $path);
   }

   private function saveAsImage($url, $file, $path)
   {
     $filename = date('YmdHis',time()) . '.jpg';
     $resource = fopen($path . DIRECTORY_SEPARATOR .  $filename, 'a');
     fwrite($resource, $file);
     fclose($resource);
     return  $path . DIRECTORY_SEPARATOR . $filename;
   }
}

// $images = [
//   // 'http://static.laravelacademy.org/wp-content/uploads/2016/05/687474703a2f2f746f6d6c696e6768616d2e636f6d2f6769746875622f6865616465722d736561726368792e706e67.png',

//   'https://steamcdn-a.akamaihd.net/steam/apps/606150/header.jpg?t=1534438110'
// ];
// $spider = new Spider();

// foreach ( $images as $url ) {
//   $spider->downloadImage($url);
// }