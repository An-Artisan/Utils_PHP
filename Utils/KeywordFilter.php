<?php

namespace common\utils;
use DfaFilter\SensitiveHelper;

class KeywordFilter  {

    protected  $redis;
    protected  $path;
    protected  $serializeObject;
    protected  $unserializeObject;
    private    $key = KEYWORD_FILTER_KEY;
    public function  __construct () {
        $this->redis = \Yii::$app->redis;
        $this->path = \Yii::getAlias('@common') . DIRECTORY_SEPARATOR . 'Keys'  . DIRECTORY_SEPARATOR . 'key.txt';
        $this->init();
    }

    protected function init() {
        if ($this->judgeRedisTree())
            $this->unserializeToReids();
        else
            $this->serializeToRedis(SensitiveHelper::init()->setTreeByFile($this->path));
    }
    protected function serializeToRedis ($object) {
        if(is_object($object)||is_array($object)) $this->serializeObject = serialize($object);
        else false;
        if (!$this->saveRedis())  return false;
        else return true;
    }
    protected  function saveRedis(){
        $result = $this->redis->set("keyword",$this->serializeObject);
        if ($result) return $this->unserializeToReids();
        else return false;
    }
    protected function judgeRedisTree() {
        if ($this->redis->exists($this->key)) return true;
        else return false;
    }

    protected function unserializeToReids() {
         $this->unserializeObject = unserialize($this->redis->get($this->key));
         return true;
    }

    /**
     * Desc: 公共调用接口，传SensitiveHelper中方法名和参数即可
     * Date: 2018/9/4
     * Time: 14:55
     * @param $methodName
     * @param $param
     * @return mixed
     * Created by StubbornGrass.
     */
    public function KeywordMethod($methodName,$param) {
        return call_user_func_array(array($this->unserializeObject, $methodName), $param);
    }



}