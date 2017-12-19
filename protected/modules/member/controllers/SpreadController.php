<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/3
 * Time: 15:04
 * Explain:推广链接
 */
class SpreadController extends MemberController
{
    /**
     * Index
     */
    public function actionIndex()
    {
        $member = $this->loadModel($this->uid);
        if (empty($member->alias)) {
            $member->alias = Common::createTempPassword($member->username);
            $member->update();
        }
        $qrcodee = Qrcodee::model()->findAll(' uid=:uid order by id DESC',array(":uid"=>$this->uid));
        $qrurl="";
        if($qrcodee){
            $qrurl=$qrcodee[0]['qrurl'];
        }
        $this->render('index', array(
            'member' => $member,'qrurl'=>$qrurl
        ));
    }

    /**
     * @param $id
     * @return Member
     * @throws CHttpException
     */
    private function loadModel($id)
    {
        $model = Member::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function actionEdit(){
        $img_src=$_POST['img'];
        $list = explode(",", $img_src);      //字符串分割
        $base64_string = $list[1];           //取后者作为内容数据
        $img = base64_decode($base64_string);            //将base64数据流解码
        $img_url='./uploads/image/'.date('YmdHis').rand(1000, 9999).'.png';
        $n = file_put_contents($img_url, $img);      //将数据保存成文件，$n是文件的字节数
        $model= new Qrcodee;
        $model->uid = $this->uid;
        $model->source = 'qrcode';
        //二维码生成
        $serial_number=date('YmdHis');
        $url='t/'.($this->uid+123456).'?utm_source=1';
        $codeurls=Common::createQrcode($img_url,$serial_number,$url);
        $model->qrurl = $codeurls;
        $model->createtime=time();
        if($model->save()){
            unlink ( $img_url );
            exit(CJSON::encode(array("status"=>200,"message"=>"生成二维码成功")));
        }
    }

    /**
     * @param ROM大师评选
     */
    public function actionRom(){
        $uid=$this->uid;
        $member = $this->loadModel($this->uid);
        $sql="SELECT COUNT(DISTINCT imeicode)as num FROM app_rom_appresource where uid={$this->uid} and finishdate BETWEEN '2016-01-01' and '2016-12-31'";
        $num = Yii::app()->db->createCommand($sql)->queryAll();
        if($num){
            $member_model=new Member();
            $num=$num[0]['num']+10000;//手机数
            $credits=0;
            if($num>=60000){
                $grade="ROM大师";
                $credits=10000;//ROM大师评选积分+10000
            }elseif($num>=30000){
                $grade="ROM专家";
                $credits=5000;//ROM大师评选积分+5000
            }elseif($num>=1000){
                $grade="ROM达人";
                $credits=1000;//ROM大师评选积分+1000
            }else{
                $grade="2016年您的有效设备太少，无法生成证书";
            }
            //判断是否已赠送积分
            $memberCreditsLog = MemberCreditsLog::model()->find("memberId=:uid and source=:source",array(":uid"=>$uid,":source"=>'rom'));
            if(!$memberCreditsLog){
                $member_model->updateCounters(array("credits"=>$credits),"id={$uid}");
                $this->checkCredits($credits,$uid,$source='rom',$member->credits);
            }

            $num=round($num+11000/10000,1) ;
        }
        $this->renderPartial('certificate',array('num'=>$num,'username'=>$member->username,'grade'=>$grade,'t'=>$uid));
    }

    protected  function checkCredits($credits,$uid,$source,$account_credits){
        $credits_model = new MemberCreditsLog();
        $credits_model->create_datetime = time();
        $credits_model->memberId = $uid;
        $credits_model->credits = $credits;
        $credits_model->remarks = "用户参加Rom大使评选获赠{$credits}积分";
        $credits_model->opid = 0;
        $credits_model->source =$source;
        $credits_model->account_credits =$credits+$account_credits;
        $credits_model->save();
    }

}