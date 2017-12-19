<?php
/**
 * Explain:地区页
 */
class AreaController extends Controller
{
    public function actionIndex()
    {
        throw new CHttpException(404, '此页面不存在');
    }

    /**
     * Ajax获得子项列表
     * @param $fid
     */
    public function actionChild($fid)
    {
        if (empty($fid)) {
            echo '';
            exit;
        }

        $result = Area::model()->findAll('up=:fid', array(':fid' => $fid));
        echo CHtml::tag('option', array('value' => ''), CHtml::encode('-请选择-'), true);
        foreach ($result as $v) {
            /* @var $v Area */
            echo CHtml::tag('option', array('value' => $v->id), CHtml::encode($v->name), true);
        }
        exit;
    }
    public function actionSql(){
        set_time_limit(0);
        $sql="select  imeicode from app_rom_appresource WHERE `uid` = '376' AND `createtime` >= '2017-07-15' AND `createtime` < '2017-10-02'  GROUP BY imeicode  having count(*)>1 ";
        $arr=yii::app()->db->createCommand($sql)->queryAll();
        $imeicode='';
        foreach ($arr as $k => $v) {
            $imeicode.='"'.$v['imeicode'].'",'; 
        }
        $imeicode=substr($imeicode,0,-1);
        // print_r($arr);exit;
        $sql="select id,imeicode,date_format(createtime,'%Y-%m-%d')as time from app_rom_appresource where imeicode in(".$imeicode.")  AND `uid` = '376' AND `createtime` >= '2017-07-15' AND `createtime` < '2017-10-02'";
        // echo $sql;exit;
        $add=yii::app()->db->createCommand($sql)->queryAll();
        // print_r($add);exit;
        $data=array();
        foreach ($add as $k => $v) {
            $data[]=$v['imeicode'].$v['time'];//当天中手机码重复
        }
        $new=array_keys(array_unique($data));//去掉当天中手机码重复，剩下的索引
        $news=array_keys($add);


        // print_r($news);exit;
        foreach ($news as $key => $value) {
           foreach ($new as $k => $v) {
               if($value==$v){
                    unset($news[$key]);
               }
           }
        }
        //此时news中存放的是 （去掉当天中手机码重复的索引）
        // print_r($news);exit;
        foreach ($news as $k => $v) {
            foreach ($add as $key => $value) {
                unset($add[$v]);
            }
        }
        // print_r($add);exit;
        // 去掉单一imeicode码
        $add=array_merge($add);
        $imi=array();
        foreach ($add as $key => $value) {
            $imi[]=$value['imeicode'];
        }
        $num=array_keys($add);//获取索引
        $imi=array_keys(array_unique($imi));//去重后获取索引
        foreach ($imi as $key => $value) {
            foreach($num as $k=>$v){
                if($value==$v){
                    unset($num[$v]);
                }
            }
        }
        //此时num中存放的是去重时，去掉的索引。
        $qw=array();//索引对应的imeicode
        foreach ($num as $key => $value) {
            foreach ($add as $k => $v) {
                if($value==$k){
                    $qw[$k]['imeicode']=$v['imeicode'];
                }
            }
        }
        $re=array();//新的数组
        foreach ($qw as $key => $value) {
            foreach ($add as $k => $v) {
                if($v['imeicode']==$value['imeicode']){
                    $re[$k]['id']=$v['id'];
                    $re[$k]['time']=$v['time'];
                    $re[$k]['imeicode']=$v['imeicode'];
                }
            }
        }


        print_r($re);exit;
       
    }
    

}