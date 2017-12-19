<?php
/**
 * Created by PhpStorm.
 * User: Peng
 * Date: 2017/1/22
 * Time: 10:25
 */
class FotoplaceController extends Controller
{
    public $layout = false;

    public function actionIndex()
    {
        if(Yii::app()->user->isGuest)
        {
            //游客
            throw new CHttpException(404,"请登录后再查看！");
        }else {
            // 获取用户信息
            $userInfo = Yii::app()->user;
            if (empty($userInfo->member_uid)) {
                throw new CHttpException(404,"请先登录后再查看！");
            } else {
                $uid = $userInfo->member_uid;
                $sql1 = "(SELECT * FROM `app_rom_appresource` WHERE `uid` = '{$uid}' AND `createtime` LIKE '%2016%'  ORDER BY id ASC limit 1)";
                $installdata1 = Yii::app()->db->createCommand($sql1)->queryAll();

                $sql2 = "(SELECT * FROM `app_member_paylog` WHERE `uid` = '{$uid}' AND `status` =1 AND `ask_time` LIKE '%2016%' ORDER BY id ASC limit 1)";
                $installdata2 = Yii::app()->db->createCommand($sql2)->queryAll();
                if (isset($installdata1)&& isset($installdata2) && count($installdata1)>0 && count($installdata2)>0){
                    
                }else{
                    throw new CHttpException(404,"数据太少,无法生成!");
                }
                // 创建文件
                $filepath = getcwd() . '/uploads/gd/' . $uid;
                $filepath = str_replace('\\', '/', $filepath);
                // 如果文件夹存在,但是图片个数不全,则删除
                if (file_exists($filepath)){
                    $filenamearr=scandir($filepath);
                    if (count($filenamearr)<10){
                        $this::delezipfile($filepath);
                    }
                }
                if (!file_exists($filepath)) {
                    mkdir($filepath, 0777);

                    // 第一页
                    $data1 = Member::model()->findAll('id=:id', array(':id' => $uid));
                    $ipInfos = $this::getCity($data1[0]['reg_ip']); //baidu.com IP地址
                    
                    if ($ipInfos['region']===$ipInfos['city']){
                        $str = $ipInfos['city'];
                    }else{
                        $str = $ipInfos['region'].$ipInfos['city'];
                    }

                    $str1 = date("Y年m月d日", $data1[0]['jointime']) . '   天气：晴

你在' . $str . '的某一个角落

使用' . $data1[0]['username'] . '作为用户名注册了速推平台账号';
                    
                    $left1 = 200;
                    $top1 = 520;
                    $fontsize1 = 30;
                    $colorArr1 = array();
                    $colorArr1[0] = 255;
                    $colorArr1[1] = 255;
                    $colorArr1[2] = 255;
                    $this->setGdPic('01.png', $str1, $left1, $top1, $fontsize1, $colorArr1);

                    // 第二页
                    $sql2 = "(SELECT DATE_FORMAT(installtime,'%Y年%m月%d日') FROM `app_rom_appresource` WHERE `uid` = '{$uid}'  AND `installtime` != '0000-00-00 00:00:00' ORDER BY installtime ASC limit 1)";
                    $installdata2 = Yii::app()->db->createCommand($sql2)->queryAll();

                    $time1 = $installdata2[0]["DATE_FORMAT(installtime,'%Y年%m月%d日')"];

                    $str2 = '经过你的不懈努力

首次数据上报时间是' . $time1;
                    $left2 = 170;
                    $top2 = 520;
                    $fontsize2 = 30;
                    $colorArr2 = array();
                    $colorArr2[0] = 97;
                    $colorArr2[1] = 62;
                    $colorArr2[2] = 24;
                    $this->setGdPic('02.png', $str2, $left2, $top2, $fontsize2, $colorArr2);
                    // 第三页
                    $sql3 = "(SELECT DATE_FORMAT(ask_time,'%Y年%m月%d日 %H:%i:%s') FROM `app_member_paylog` WHERE `uid` = '{$uid}' AND `status` =1 ORDER BY id ASC limit 1)";
                    $installdata3 = Yii::app()->db->createCommand($sql3)->queryAll();
                    $ask_time = $installdata3[0]["DATE_FORMAT(ask_time,'%Y年%m月%d日 %H:%i:%s')"];
                    $str3 = $ask_time . '

你第一次点击了提现按钮';
                    $left3 = 330;
                    $top3 = 370;
                    $fontsize3 = 30;
                    $colorArr1 = array();
                    $colorArr1[0] = 10;
                    $colorArr1[1] = 116;
                    $colorArr1[2] = 94;
                    $this->setGdPic('03.png', $str3, $left3, $top3, $fontsize3, $colorArr1);
                    // 第四页
                    $sql4 = "(SELECT * FROM `app_member_paylog` WHERE `uid` = '{$uid}' AND `status` =1 ORDER BY id ASC limit 1)";
                    $installdata4 = Yii::app()->db->createCommand($sql4)->queryAll();

                    $answer_time = date('Y年m月d日', strtotime($installdata4[0]['answer_time']));
                    $str4 = '经过多日等待

你在速推获取第一笔收益

时间是：' . $answer_time . '

隔着屏幕小速都能感受到

你嘴角微扬的喜悦

从此我们之间架起了信任的桥梁';
                    $left4 = 300;
                    $top4 = 140;
                    $fontsize4 = 30;
                    $colorArr1 = array();
                    $colorArr1[0] = 253;
                    $colorArr1[1] = 215;
                    $colorArr1[2] = 52;
                    $this->setGdPic('04.png', $str4, $left4, $top4, $fontsize4, $colorArr1);
                    // 第五页
                    $sql5 = "(SELECT * FROM `app_member_paylog` WHERE `uid` = '{$uid}' AND `status` =1 AND `ask_time` LIKE '%2016%' ORDER BY sums DESC limit 1)";
                    $installdata5 = Yii::app()->db->createCommand($sql5)->queryAll();
                    $arr5 = array();
                    $arr5['month'] = str_replace('0', '', substr($installdata5[0]['answer_time'], 5, 2)) . "月";
                    $arr5['sums'] = $installdata5[0]['sums'];
                    $str5 = $arr5['month'] . '

是你这一年中提现最高的月份

达到了：' . $arr5['sums'] . '元';
                    $left5 = 300;
                    $top5 = 310;
                    $fontsize5 = 30;
                    $colorArr1 = array();
                    $colorArr1[0] = 255;
                    $colorArr1[1] = 255;
                    $colorArr1[2] = 255;
                    $this->setGdPic('05.png', $str5, $left5, $top5, $fontsize5, $colorArr1);
                    // 第六页
                    $sql6 = "(SELECT count(distinct imeicode)  FROM `app_rom_appresource` WHERE `uid` = '{$uid}' AND `createtime`  LIKE '%2016%'  ORDER BY id ASC)";
                    $installdata6 = Yii::app()->db->createCommand($sql6)->queryAll();

                    $num = $installdata6[0]['count(distinct imeicode)'];
                    $amount = $num * 52;
                    $arr6 = array();
                    $arr6['num'] = $num;
                    $arr6['amount'] = $amount;
                    $str6 = '2016年，大约' . $num . '部手机

因你更稳定的运行

你为祖国人民节省了大约

' . $amount . '元人民币';
                    $left6 = 300;
                    $top6 = 300;
                    $fontsize6 = 30;
                    $colorArr1 = array();
                    $colorArr1[0] = 0;
                    $colorArr1[1] = 0;
                    $colorArr1[2] = 0;
                    $this->setGdPic('06.png', $str6, $left6, $top6, $fontsize6, $colorArr1);
                    // 第七页
                    $sql6 = "(SELECT SUM(sums) FROM `app_member_paylog` WHERE `uid` = '{$uid}' AND `status` =1 AND `ask_time` LIKE '%2016%')";
                    $installdata6 = Yii::app()->db->createCommand($sql6)->queryAll();
                    $allincome = $installdata6[0]['SUM(sums)'];
                    $str7 = $allincome . '元';
                    $left7 = 230;
                    $top7 = 320;
                    $fontsize7 = 50;
                    $colorArr1 = array();
                    $colorArr1[0] = 255;
                    $colorArr1[1] = 255;
                    $colorArr1[2] = 255;
                    $this->setGdPic('07.png', $str7, $left7, $top7, $fontsize7, $colorArr1);
                    // 第八页
                    $jiontime = $data1[0]['jointime'];
                    $endtime = strtotime('2016-12-31 23:59:59');
                    if ($jiontime < $endtime) {
                        $daynum = floor(($endtime - $jiontime) / 86400);
                    }
                    $str8 = $daynum;
                    $left8 = 540;
                    $top8 = 450;
                    $fontsize8 = 40;
                    $colorArr1 = array();
                    $colorArr1[0] = 255;
                    $colorArr1[1] = 255;
                    $colorArr1[2] = 255;
                    $this->setGdPic('08.png', $str8, $left8, $top8, $fontsize8, $colorArr1);
                    $this->render("index");
                } else {
                    $this->render("index");
                }
            }
        }
    }

    // 根据ip获取地址
    private function getCity($ip = '')
    {
        try {
            if ($ip == '') {
                $url = "http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json";
                $ip = json_decode(file_get_contents($url), true);
                $data = $ip;
            } else {
                $url = "http://ip.taobao.com/service/getIpInfo.php?ip=" . $ip;
                $ip = json_decode(file_get_contents($url));
                if ((string)$ip->code == '1') {
                    return false;
                }
                $data = (array)$ip->data;
            }
            return $data;
        }catch (Exception $e){
            throw new CHttpException(404,"请刷新页面后重新访问!");
        }
    }


    // 给图片添加文字水印
    private function setGdPic($imgname,$str,$left,$top,$fontsize,$colorArr){
        $userInfo1 = Yii::app()->user;
        $uid1=$userInfo1->member_uid;
        header("Content-type:text/html;charset=utf-8");
        $dst_path = getcwd().'/css/fotoplace/images/'.$imgname;
        $dst_path = str_replace('\\','/',$dst_path);
    //创建图片的实例
        $dst = imagecreatefromstring(file_get_contents($dst_path));
    //打上文字
        $fontpath = getcwd()."/css/fotoplace/msyh.ttf";
        $font = str_replace('\\','/',$fontpath);//字体
        $t_color = imagecolorallocatealpha($dst, $colorArr[0], $colorArr[1], $colorArr[2], 0);//最后一个参数值越大越透明
        $t_color1 = imagecolorallocatealpha($dst, 0, 0, 0, 0);//最后一个参数值越大越透明
        imagefttext($dst, $fontsize, 0, $left+1, $top, $t_color1, $font,  $str);
        imagefttext($dst, $fontsize, 0, $left, $top, $t_color, $font,  $str);
    //输出图片
        list($dst_w, $dst_h, $dst_type) = getimagesize($dst_path);
        $imgpath = $fontpath = getcwd()."/uploads/gd/".$uid1.'/'.$imgname;
        $imgpath = str_replace('\\','/',$imgpath);
        switch ($dst_type) {
            case 1://GIF
//                header('Content-Type: image/gif');
                imagegif($dst);
                break;
            case 2://JPG
//                header('Content-Type: image/jpeg');
                imagejpeg($dst);
                break;
            case 3://PNG
//                header('Content-Type: image/png');
                imagepng($dst,$imgpath);
                break;
            default:
                break;
        }
        imagedestroy($dst);
    }


    // 删除图片个数不足8个的文件夹
    private function delezipfile($prefile){
        // 判断文件是否存在
        if (file_exists($prefile)){
            // 判断是不是文件夹
            if(is_dir($prefile)){
                $file_list= scandir($prefile);
                foreach ($file_list as $file)
                {
                    if( $file!='.' && $file!='..')
                    {
                        @unlink($prefile.'/'.$file);
                    }
                }
                @rmdir($prefile);  //这种方法不用判
            }
        }
    }



}