<?php
/**
 * Created by PhpStorm.
 * User: Peng
 * Date: 2017/1/3
 * Time: 11:06
 */
class IncomeListWidget extends CWidget {
    public $num = 1;
    public $order = "id";
    public $array = '';
    public function init()
    {
        $array = array(array('name'=>'马汉超','province'=>'安徽','amount'=>'347479'),array('name'=>'任悠','province'=>'北京','amount'=>'337859'),array('name'=>'姚盖','province'=>'重庆','amount'=>'335784')
        ,array('name'=>'王旭飞','province'=>'福建','amount'=>'335377'),array('name'=>'宋浩明','province'=>'甘肃','amount'=>'303363'),array('name'=>'肖志','province'=>'广东','amount'=>'302707'),array('name'=>'梁嘉豪','province'=>'辽宁','amount'=>'293215')
        ,array('name'=>'周国伟','province'=>'贵州','amount'=>'264501'),array('name'=>'张晓扬','province'=>'辽宁','amount'=>'243784'),array('name'=>'李易山','province'=>'辽宁','amount'=>'177153'),array('name'=>'韩鹏辉','province'=>'北京','amount'=>'173508')
        ,array('name'=>'彭明强','province'=>'河南','amount'=>'160402'),array('name'=>'柳天一','province'=>'吉林','amount'=>'155708'),array('name'=>'张凯旋','province'=>'江苏','amount'=>'147900'),array('name'=>'杨大海','province'=>'河南','amount'=>'139757')
        ,array('name'=>'赵加一','province'=>'辽宁','amount'=>'116422'),array('name'=>'路遥','province'=>'广东','amount'=>'88648'),array('name'=>'范进','province'=>'江苏','amount'=>'21938'),array('name'=>'李一凡','province'=>'广东','amount'=>'21056'),array('name'=>'赵国栋','province'=>'浙江','amount'=>'15929'));

        $this->render("index",array("data"=>$array));
    }

    public function run()
    {

    }
}