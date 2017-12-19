<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/3
 * Time: 17:14
 * Name: 代理商推广链接地址变更
 */
class DomainsController extends Controller
{
    public function actionIndex()
    {
        //如果用推广链接，即代理商别名打开首页，保存代理商别名

        $this->render('index');
    }
    private function test($uid){
        $member =  Member::model()->findByPk($uid);
        $is_show=true;
        if($member){
            $username=$member->username;
            $sql="SELECT COUNT(DISTINCT imeicode)as num FROM app_rom_appresource where uid={$uid} and finishdate BETWEEN '2016-01-01' and '2016-12-31'";
            $num = Yii::app()->db->createCommand($sql)->queryAll();
            if($num){
                $member_model=new Member();
                $num=$num[0]['num'];//手机数
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
                    $is_show=false;
                    $grade="2016年您的有效设备太少，无法生成证书";
                }
                //判断是否已赠送积分
                $share = Share::model()->findAll(array(
                    'select'=>array('id'),
                    'group' => 'ip',
                    'condition' => 'uid=:uid and sid=:sid',
                    'params' => array(":uid"=>$uid,":sid"=>2),
                ));
                //var_dump(count($share));exit;
              if(count($share)>=2){
                  $memberCreditsLog = MemberCreditsLog::model()->find("memberId=:uid and source=:source",array(":uid"=>$uid,":source"=>'rom'));
                  if(!$memberCreditsLog){
                      $member_model->updateCounters(array("credits"=>$credits),"id={$uid}");
                      $this->checkCredits($credits,$uid,$source='rom',$member->credits);
                  }
              }

                $num=round($num/10000,1)."万" ;
            }
        }else{
            $num='';$grade='';$username='';
        }
        $this->renderPartial('certificate',array('num'=>$num,'username'=>$username,'grade'=>$grade,'is_show'=>$is_show,"t"=>$uid));
    }

    /**
     * @param ROM大师评选
     */
    public function actionCertificate(){

        $cookies = Yii::app()->request->getCookies();
        $domain = isset($cookies['agent']) ? $cookies['agent']->value : '';
        $utm_source = isset($cookies['utm_source']) ? $cookies['utm_source']->value : '';
        $uid=$domain-123456;
        $this->test($uid);

    }
    public function actionCert(){

        $cookies = Yii::app()->request->getCookies();
        $utm_source = isset($cookies['utm_source']) ? $cookies['utm_source']->value : '';
        if(Yii::app()->user->isGuest){
            //throw new CHttpException(400, '请先登录');
            $this->redirect(array('/login'));
        }else{
            if(isset(Yii::app()->user->member_id))
            {
                $this->test(Yii::app()->user->member_id);
            }
            else
            {
                throw new CHttpException(404,"用户未登陆");
            }

        }


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
    /**
     * @param $t 用户id+123456
     * @param  推广链接
     */
    public function actionIn($t)
    {
        //如果用推广链接，即代理商别名打开首页，保存代理商别名
        $utm_source = Yii::app()->request->getQuery('utm_source');

        $utm_source=isset($utm_source)? $utm_source:0;
        $member=Member::model()->findByPk($t-123456);
        if($member){
            if( !empty($t)){
                $share_model = Share::model()->find(' uid=:uid and sid=:sid order by id DESC',array(":uid"=>$t-123456,":sid"=>$utm_source));
                $c = date('Y-m-d');
                $ip=Common::getIp();
                $user = Yii::app()->user;
                $is_login=$user->getState('member_uid');
                if($share_model && $c==date('Y-m-d',$share_model->date) && $ip==$share_model->ip && $is_login == $share_model->is_login){
                    //$share_model->ip=$ip;
                    $share_model->date=time();
                    if($share_model->save()){
                        $share_model->updateCounters(array("hits"=>1),"id={$share_model->id}");//渠道注册人数+1
                    }
                }else{
                    $share_model=new Share();
                    $share_model->hits=1;
                    $share_model->ip=$ip;
                    $share_model->date=time();
                    $share_model->uid=$t-123456;
                    $share_model->sid=$utm_source;
                    $share_model->is_login=$is_login;
                    $share_model->save();
                }
            }
            if (!empty($t)) {
                $cookeis = Yii::app()->request->getCookies();
                $cookeis->add('agent', new CHttpCookie('agent', $t));
                $cookeis->add('utm_source', new CHttpCookie('utm_source', $utm_source));

            }
            switch($utm_source){//不同链接方式跳转不同页面
                case 0:
                    //var_dump($this->actions());exit;
                    if(Common::isMobile())
                    { //手机
                        $this->redirect(array('/site/tgzcPhone'));
                    }
                    else
                    {
                        $this->pageTitle = "推广活动注册账号";
                        $this->layout = 'tgzc';
                        $model = new Member('tgzc');
                        $this->render("tgzc",array("model"=>$model));
                    }
                    //$this->redirect(array('/domains/tgzc'));
                    //$this->actionTgzcPC();
                    break;
                case 1:
                    $this->pageTitle = "推广活动注册";
                    $this->redirect(array('/site/tgzcPhone'));
                    break;
                case 2:
                    $this->pageTitle = "Rom大师评选活动";
                    $this->test($t-123456);
                    break;
                default:
                    $this->redirect(array('/site/index'));
                    break;

            }

        }else{
            throw new CHttpException(404,"非法操作");
        }


    }



}