<?php
namespace common\utils;


class UploadTailorPhoto {

    public function upload($cover,$cloudPath) {

        $spider = new GetRemotePhoto();
        $path = \Yii::$app->basePath . DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . 'images';
        $result  = $spider->downloadImage($cover,$path);
//        $result  = $spider->downloadImage("http://static.laravelacademy.org/wp-content/uploads/2016/05/687474703a2f2f746f6d6c696e6768616d2e636f6d2f6769746875622f6865616465722d736561726368792e706e67.png",$path);
        $filepath = $result;
        $targetpath = \Yii::$app->basePath . DIRECTORY_SEPARATOR. 'web' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR;
        $photo = new PhotoTailor();
        $filepath = $photo->imagecropper($filepath,270,202,$targetpath);
        $filename = date('YmdHis');
        $result = \Yii::$app->cos->uploadFile($cloudPath  .'/' . $filename . '.jpg',$filepath,[]);
        @unlink($filepath);
        if ($result) {
            $uploadPath = \Yii::$app->params['domain.cos'] . DIRECTORY_SEPARATOR .$cloudPath . $filename .'.jpg';
            return $uploadPath;
        } else return $result;

//        var_dump($result,$filename .'.jpg');exit;
    }

}