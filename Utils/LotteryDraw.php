<?php

namespace common\utils;


/**
 * Desc 抽奖算法
 * Example:

    $prizes = array(
    '0' => array('id'=>1,'prize'=>'盲僧李青限定皮肤 SKT T1','percent'=>100),
    '1' => array('id'=>2,'prize'=>'龙的传人 李青','percent'=>5),
    '2' => array('id'=>3,'prize'=>'至高之拳 李青','percent'=>10),
    '3' => array('id'=>4,'prize'=>'地下拳王 李青','percent'=>12),
    '4' => array('id'=>5,'prize'=>'防弹武僧 李青','percent'=>22),
    '5' => array('id'=>6,'prize'=>'很遗憾，你本次抽奖未中奖。','percent'=>0),
    );
    $lottery = new LotteryDraw($prizes);
    dd($lottery->prizesFiltering());

 * Class LotteryDraw
 * @package common\utils
 */
class LotteryDraw {


    /**
     * Desc:抽奖列表
     * @var null
     */
    protected $prizes = null;


    /**
     * 初始化抽奖数据
     * 数据格式如下：
             $prizes = array(
                 '0' => array('id'=>1,'skin_id' => 5,'prize'=>'盲僧李青限定皮肤 SKT T1','percent'=>1),
                 '1' => array('id'=>2,'skin_id' => 6,'prize'=>'龙的传人 李青','percent'=>5),
                 '2' => array('id'=>3,'skin_id' => 7,'prize'=>'至高之拳 李青','percent'=>10),
                 '3' => array('id'=>4,'skin_id' => 8,'prize'=>'地下拳王 李青','percent'=>12),
                 '4' => array('id'=>5,'skin_id' => 9,'prize'=>'防弹武僧 李青','percent'=>22),
                 '5' => array('id'=>6,'skin_id' => 10,'prize'=>'传统僧侣 李青','percent'=>50),
                 '6' => array('id'=>6,'skin_id' => 11,'prize'=>'很遗憾，你本次抽奖未中奖。','percent'=>50),
             );
     * 数据说明：
            二维数组下的id代表皮肤id
            二维数组下的prize代表皮肤名称
            二维数组下的percent代表抽中百分比
     * 注意：
            二维数组下所有的percent总和除以当前percent就是该款皮肤的几率
            公式：count(percent) / prizes[0] = 0.01
            表示如果总和为100，当前percent为1，那么抽中第一款皮肤的几率为1%
     * LotteryDraw constructor.
     * @param $prizes
     */
    public function __construct ($prizes) {

        $this->prizes = $prizes;

    }


    public function prizesFiltering() {

        // 如果传入的数组长度为空的话则返回false
        if (!count($this->prizes)) return false;

        foreach ($this->prizes as $key => $value) $prizes[$value['id']] = $value['percent'];
        // 根据下标重新排序
        asort($prizes);

        //根据概率获取中奖皮肤id
        $luckyId = $this->getLuckySkin($prizes);

        //中奖皮肤信息
        $resulst['lucky'] = $this->prizes[$luckyId - 1];
        //将中奖项从数组中删除
        unset($this->prizes[$luckyId - 1]);
        //打乱数组顺序
        shuffle($this->prizes);
        $newPrizes = [];
        for($i = 0;$i < count($this->prizes); $i++){
            $newPrizes[] = $this->prizes[$i];
        }
        $resulst['unlucky'] = $newPrizes;
        return $resulst;
    }


    protected function getLuckySkin($prizes) {
        $result = 0;

        //概率数组的总概率精度
        $prizeSum = array_sum($prizes);

        //概率数组循环
        foreach ($prizes as $key => $percent) {

            // 获取1 - 概率基数总数 之间的一个随机值
            $randNumber = mt_rand(1, $prizeSum);

            /**
             *
             * 开始是从1,100这个概率范围内筛选随机数是否在他的出现概率范围之内，
             * 如果不在，则将概率空减，也就是概率基数的总值减去刚刚的那个数字的概率空间，
             * 假如第一个皮肤概率为1，那么第一次没有抽中，那么随机数随机终值为
             * $prizeSum -= $percent; 也就是100-1 =99 也就是说第二个数是在1-99
             * 这个范围内筛选的。这样筛选到最终，总会有一个数满足要求。如果不想抽中
             * 某一个奖，直接设置percent为0即可。
             */
            if ($randNumber <= $percent) {
                $result = $key;
                break;
            }   $prizeSum -= $percent;
        }
        unset ($prizes);
        return $result;
    }


}