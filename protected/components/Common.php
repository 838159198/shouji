<?php

/**
 * Explain:工具类--保留
 */ 
class Common
{
    /** 每页显示信息条数 */
    const PAGE_SIZE = 20;
    const PAGE_SIZE_2 = 50;
    /** 信息条数 */
    const PAGE_SIZE_LESS = 10;
    /** 无穷大 */
    const PAGE_INFINITY = 1000;

    /** 缓存超时时间 */
    const CACHE_TIME = 300; //5分钟

    /** 用户身份-普通用户 */
    const USER_TYPE_MEMBER = 'Member';
    /** 用户身份-管理员 */
    const USER_TYPE_MANAGE = 'Manage';
    /** 用户身份-代理商 */
    const USER_TYPE_AGENT = 'Agent';
    /** 用户身份-代理商子用户 */
    const USER_TYPE_SYNTHSIZE = 'Synthsize';
    /** 用户身份-原线下地推*/
    const USER_TYPE_DITUI= 'Ditui';
    /** 用户身份-经销商 */
    const USER_TYPE_DEALER= 'Dealer';
    /** 用户身份-微信短信网站 */
    const USER_TYPE_MSG= 'Msg';
    /** 用户身份-广告合作 */
    const USER_TYPE_ADVERT= 'Advert';
    /** 用户身份-线下地推 */
    const USER_TYPE_NEWDT= 'Newdt';
    /** 用户身份-其它 */
    const USER_TYPE_OTHER= 'Other';

    /** 编码 */
    const ENCODING = 'UTF-8';

    /** 网站资源路径 */
    const DIR_STORAGE = 'storageDir';
    /** xml */
    const DIR_XML = 'xmlDir';
    /** js */
    const DIR_JS = 'jsDir';
    /** css */
    const DIR_CSS = 'cssDir';
    /** img */
    const DIR_IMG = 'imgDir';
    /** excel */
    const DIR_EXCEL = 'excelDir';
    /** 统计服务器地址 */
    const STAT_SERVER = 'statServer';
    /** 静态文件地址 */
    const STATIC_SERVER = 'staticServer';


    /** 可用 */
    const STATUS_TRUE = 1;
    /** 不可用 */
    const STATUS_FALSE = 0;

    /**
     * 系统定义文件夹路径
     * @param $name
     * @return string
     */
    public static function getAppParam($name)
    {
        return Yii::app()->params[$name];
    }

    /**
     * @param $url
     * @param $message
     */
    public static function redirect($url, $message)
    {
        Yii::app()->controller->redirect(array('/redirect/index', 'url' => $url, 'message' => $message));
        exit;
    }

    /**
     * header
     */
    public static function header()
    {
        header('Content-Type: text/xml');
    }

    /**
     * 验证是否是本站提交的数据，防止跨站攻击
     * @return bool
     */
    public static function checkThisSite()
    {
        $referer = Yii::app()->request->urlReferrer;
        $host = Yii::app()->request->hostInfo;
        return strstr($referer, $host) == $referer;
    }

    /**
     * 根据参数获得参数长度的随机字符串
     * @param $length
     * @param string $type [number,string,all]
     * @return string
     */
    public static function getRadnStr($length, $type = 'number')
    {
        $chars = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
        switch ($type) {
            case 'number':
                $chars = '0123456789';
                break;
            case 'string':
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case 'all':
                break;
        }
        $str = '';
        $max = strlen($chars) - 1;
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[mt_rand(0, $max)];
        }
        return $str;
    }

    /**
     * 清理字符串中的HTML标签、空格等多余信息
     * @param $str
     * @return mixed
     */
    public static function clearTags($str)
    {
        $str = str_replace(' ', '', strip_tags($str));
        $str = str_replace('&nbsp;', '', $str);
        return $str;
    }

    /**
     * 截取字符串
     * @param $str
     * @param $lang
     * @return string
     */
    public static function substr($str, $lang)
    {
        if (empty($str) || empty($lang)) return '';
        return mb_substr(Common::clearTags($str), 0, $lang, self::ENCODING);
    }


    /**
     * 创建翻页
     * @param CPagination $paginaton
     */
    public static function createPage(CPagination $paginaton)
    {
        Yii::app()->controller->widget('CLinkPager', array(
            'header' => '',
            'pages' => $paginaton,
//    'cssFile'=>false
        ));
    }

    /**
     * @return string
     */
    public static function getIp()
    {
        if (isset($_SERVER["HTTP_CDN_SRC_IP"])) {
            return $_SERVER["HTTP_CDN_SRC_IP"];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }

    /**
     * @param $ip
     * @return string
     */
    public static function ip2long($ip)
    {
        return sprintf('%u', ip2long($ip));
    }


    /**
     * 根据参数返回组成的URL地址
     * @param args ..
     * @return string
     */
    public static function getUrl()
    {
        $args = func_get_args();
        if (empty($args)) {
            return Yii::app()->baseUrl;
        }
        return Yii::app()->baseUrl . implode('', $args);
    }

    /**
     * 根据参数提取$_POST数组值
     * @param args ..
     * @return array
     */
    public static function post2Array()
    {
        $args = func_get_args();
        $post = array();
        foreach ($args as $v) {
            $post[$v] = isset($_POST[$v]) ? $_POST[$v] : false;
        }
        return $post;
    }

    /**
     * 根据参数提取$_GET数组值
     * @param args ..
     * @return array
     */
    public static function get2Array()
    {
        $args = func_get_args();
        $post = array();
        foreach ($args as $v) {
            $post[$v] = isset($_GET[$v]) ? $_GET[$v] : false;
        }
        return $post;
    }

    /**
     * 根据参数判断参数中是否有为false的值，如果有返回true
     * @param array $args
     * @return bool
     */
    public static function verifyFalse($args)
    {
        if (!is_array($args)) {
            return true;
        }
        foreach ($args as $v) {
            if ($v === false) {
                return true;
            }
        }
        return false;
    }
    /**
     * 排除公司内测试机
     * @return array
     */
    public static function getExceptList()
    {
        return array(
            '351878061865246',
            '860770020460215',
            '864690027292393',
            '863177025078231',
            '868256020596432',
            '866769021264109',
            '867931025955962',
            '868201023263283',
            '868715024270294',
            '359320050933117',
            '864501020163962',
            '866769021864106',
            '866046029501984',
            '864690022033537',
            '869092020283815',
            '861795032193149',
            '862023030583651',
            '869092020283823',
            '862023030583644',
            '867931026158004',
            '860034032439356',
            '860034032439364',
            '353919025680137',
            '864426032755144',
            '864426032755136',
            '862304030359171',
            '862304030359176',
            '868623028454791',
            '990005629935731',
            '866004036641392',
            '861084037358720',
            '866412033642141',
            '866048030208674',
            '862932030084454',
            '864100031988226',
            '861010033685899',
            '865471032294362',
            '863991035726971',
            '865628036914630',
            '866010032504096',
            '869409020650977',
            '866697033610896',
            '863208031370617',
            '868970020313412'



        );
    }
    /**
     * 生产二维码
     * @return string
     */
    public static function phpQrcode($serial_number)
{
    include "protected/extensions/PHPQRCODE/phpqrcode.php";
    $value = 'http://www.sutuiapp.com/'.$serial_number;
    $errorCorrectionLevel = 'H';
    $matrixPointSize = 4;
    QRcode::png ( $value, 'uploads/qrcode/'.$serial_number.'.png', $errorCorrectionLevel, $matrixPointSize, 2 );
    $logo = 'css/site/images/app_icon_60.png';
    $QR = 'uploads/qrcode/'.$serial_number.'.png';
    if ($logo !== FALSE) {
        $QR = imagecreatefromstring ( file_get_contents ( $QR ) );
        $logo = imagecreatefromstring ( file_get_contents ( $logo ) );
        $QR_width = imagesx ( $QR );
        $QR_height = imagesy ( $QR );
        $logo_width = imagesx ( $logo );
        $logo_height = imagesy ( $logo );
        $logo_qr_width = $QR_width / 4;
        $scale = $logo_width / $logo_qr_width;
        $logo_qr_height = $logo_height / $scale;
        $from_width = ($QR_width - $logo_qr_width) / 2;
        imagecopyresampled ( $QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height );
    }
    $codeurl='uploads/qrcode/'.$serial_number.'.png';
    imagepng ( $QR, $codeurl);
    return "/".$codeurl;
}
    public static function createQrcode($logo,$serial_number,$url)
    {
        include "protected/extensions/PHPQRCODE/phpqrcode.php";
        $host = 'http://'.$_SERVER['HTTP_HOST'];
        $value = $host.'/'.$url;
        $errorCorrectionLevel = 'H';
        $matrixPointSize = 5;
        QRcode::png ( $value, 'uploads/qrcode/'.$serial_number.'.png', $errorCorrectionLevel, $matrixPointSize, 0);
        $logo=empty($logo)? 'css/site/images/app_icon_60.png':$logo;
        $QR = 'uploads/qrcode/'.$serial_number.'.png';
        if ($logo !== FALSE) {
            $QR = imagecreatefromstring ( file_get_contents ( $QR ) );
            $logo = imagecreatefromstring ( file_get_contents ( $logo ) );
            $QR_width = imagesx ( $QR );
            $QR_height = imagesy ( $QR );
            $logo_width = imagesx ( $logo );
            $logo_height = imagesy ( $logo );
            $logo_qr_width = $QR_width / 5;
            $scale = $logo_width / $logo_qr_width;
            $logo_qr_height = $logo_height / $scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;
            imagecopyresampled ( $QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height );
        }
        $codeurl='uploads/qrcode/'.$serial_number.'.png';
        imagepng ( $QR, $codeurl);
        return "/".$codeurl;
    }
    /**
     * 获得实际路径
     * @param $filename
     * @return string
     */
    public static function getPath($filename)
    {
        $path = dirname(Yii::app()->basePath) . $filename;
        if (DIRECTORY_SEPARATOR == '\\') {
            $path = str_replace('/', DIRECTORY_SEPARATOR, $path);
        } else {
            $path = str_replace('\\', DIRECTORY_SEPARATOR, $path);
        }
        return $path;
    }
    /*
    * 邀请码
    * */
    public static function getInvitationcode($uid)
    {
        // $data = array('39'=>"st0001",'42'=>"st9900",'41'=>"st0002");
        $sql='SELECT mid,code from `app_invitationcode`';
        $arr=yii::app()->db->createCommand($sql)->queryAll();
        $data=array();
        if(!empty($arr)){
            foreach ($arr as $k => $v) {
                $data[$v['mid']]=$v['code'];
            }
        }
        $result="";
        if(!empty($uid))
        {
            //根据uid获取
            if(is_numeric($uid))
            {
                foreach($data as $key=>$val)
                {
                    if($key==$uid)
                    {
                        $result=$val;
                        break;
                    }
                }
            }
            else
            {
                //根据邀请码获取
                foreach($data as $key=>$val)
                {
                    if($val==$uid)
                    {
                        $result=$key;
                        break;
                    }
                }
            }

        }
        else
        {

        }


        return $result;
    }
    /**
     * CDN推送
     * @param string $member
     * @param string $user
     * @param string $pass
     */
    public static function push($member = '', $user = 'woai310', $pass = 'push@woai310')
    {
        $host = 'http://wscp.lxdns.com:8080/wsCP/servlet/contReceiver?username=' . $user; //&passwd=md5&url=url1;url2;...&dir=dir1;dir2&delaytime=delayseconds
        $url = 'user.awangba.com.cn/' . $member . '.xml';
        $md5 = MD5($user . $pass . $url);
        $host .= '&passwd=' . $md5 . '&url=' . $url;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_URL, $host);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $output = curl_exec($ch);
        curl_close($ch);

    }
    /**
     * 新版CDN推送
     * @param string $url
     */
    public static function newPush($url){
        $host =  'http://df.sutuiapp.com/' . $url;

        $data=array("type"=>'file',"urls"=>$host);
        Common::refresh($data);
    }
    /**
     * 提交刷新
     * @param $data
     * @return array
     */
    public static function refresh($data) {
        $token = new Token();
        $token = $token->token();
        if(!$token) {
            return array(
                'code' => 0,
                'message' => 'unable to get token'
            );
        }
        include "StringFilter.php";
        //刷新类型 file 文件 dir 目录
        $type = isset($data['type']) && !empty($data['type']) ? check_plain(trim($data['type'])) : '';
        //刷新url
        $urls = isset($data['urls']) && !empty($data['urls']) ? trim(urldecode($data['urls'])) : '';
        //url分隔符，多个url使用该符号分隔，默认,
        $partition = isset($data['partition']) && !empty($data['partition']) ? check_plain(trim($data['partition'])) : ',';
        if(empty($type) || empty($urls)) {
            return array(
                'code' => 0,
                'message' => 'type and urls are required',
            );
        }
        $url = Request::$api_url . '/API/cdn/refresh';
        $send_data = array(
            'token' => $token,
            'type' => $type,
            'urls' => $urls,
            'partition' => $partition
        );

        $return = Request::sendRequest($url, $send_data);
        return $return;
    }

    /**
     * 创建一个get请求
     * @param $url
     * @return string
     */
    public static function createGet($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CONNECTION_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 30000);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output === false ? '' : $output;
    }

    /**
     * 创建一个post请求
     * @param $url
     * @param $data
     * @return string
     */
    public static function createPost($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CONNECTION_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 30000);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

//            curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 0);
//            curl_setopt($ch, CURLOPT_PROXY, '127.0.0.1:80');
        $output = curl_exec($ch);
        curl_close($ch);
        return $output === false ? '' : $output;
    }

    /**
     * 获得二级域名字符串
     * @return string
     */
    public static function getUrlDomain()
    {
        $url = $_SERVER['HTTP_HOST'];
        $domain = '';
        switch ($url) {
            case 'lm.txwp.com':
                $domain = 'txwp';
                break;
            case 'www.gtwb8.com':
                $domain = 'z';
                break;
            default:
                $reg = '/^(\w+)/';
                $matches = '';
                preg_match($reg, $url, $matches);

                if (count($matches) == 0) {
                    return '';
                }
                $dict = array('', 'www', 'sutuiapp', 'yii', 'user','sutui','192');
                $domain = strtolower($matches[0]);
                if (in_array($domain, $dict)) {
                    return '';
                }
                break;
        }

        return $domain;
    }

    /**
     * 打印json并停止程序运行
     * @param array|obj $arr
     * @param string $cb
     */
    public static function printJson($arr, $cb = null)
    {
        if (empty($cb)) {
            echo json_encode($arr);
        } else {
            echo $cb . '(' . json_encode($arr) . ');';
        }
        exit;
    }
    /**
     * 打印json并停止程序运行
     * @param array|obj $arr
     * @param string $url
     * @param string $j
     * @param string $cb
     */
    public static function printJson_tj($arr, $url, $j,$cb = null)
    {
        if (empty($cb)) {
            $echoin=json_encode($arr);
            //日志功能
            /*$createtime=date("Y-m-d H:i:s");
            $sql = "INSERT INTO `app_rom_log` (`title`, `request`, `result`, `createtime`) VALUES ('{$j}','{$url}','{$echoin}','{$createtime}')";
            Yii::app()->db->createCommand($sql)->execute();*/

            echo $echoin;

        } else {
            echo $cb . '(' . json_encode($arr) . ');';
        }
        exit;
    }

    /**
     * 格式化输入的字符串
     * @param $str
     * @return string
     */
    public static function strFormat($str)
    {
        $str = str_replace("\r\n", '<br />', $str);
        $str = str_replace("\n", '<br />', $str);
        return $str;
    }

    /**
     * 序列化对象或数组
     * @param $obj
     * @return string
     */
    public static function serialize($obj)
    {
        $data = json_encode($obj);
        $data = gzcompress($data);
        $data = base64_encode($data);
        return $data;
    }

    /**
     * 反序列化
     * @param $str
     * @return array|Object
     */
    public static function unserialize($str)
    {
        $data = null;
        try {
            $str = str_replace(' ', '+', $str);
            $data = base64_decode($str);
            if (empty($data)) return array();

            $data = @gzuncompress($data);
            $data = @json_decode($data, true);
        } catch (Exception $e) {
        }
        return $data;
    }

    /**
     * 生成当前使用的临时密码
     * @param string $key
     * @return string
     */
    public static function createTempPassword($key = '')
    {
        $key .= 'by www.awangba.com/manage/temp/password';
        return md5(date('YmdH') . $key);
    }

    /**
     * 验证两个字符串是否相等
     * @param $str1
     * @param $str2
     * @return bool
     */
    public static function equals($str1, $str2)
    {
        return strtolower(trim($str1)) == strtolower(trim($str2));
    }

    /**
     * 获得当前月份及向前num个月份的（年-月）列表
     * @param int $num
     * @return array
     */
    public static function getDateMonthList($num = 3)
    {
        $dates = array();
        $dates[date('Y-m')] = date('Y-m');
        for ($i = 1; $i < $num; $i++) {
            $date = date('Y-m', strtotime('-' . $i . ' month'));
            $dates[$date] = $date;
        }
        return $dates;
    }

    /**
     * 判断开发环境
     * @param string $environment
     * @return bool
     */
    public static function isDevelopment($environment = 'development')
    {
        return ENVIRONMENT == $environment;
    }

    /**
     * 数组转xml
     * @param $arr
     * @return mixed
     */
    public static function array2Xml($arr)
    {
        $str = '<?xml version="1.0" encoding="UTF-8"?><root url="www.awangba.com" sum=""></root>';
        $xml = simplexml_load_string($str);
        foreach ($arr as $_a) {
            $item = $xml->addChild('item');
            foreach ($_a as $k => $v) {
                $item->addChild($k, $v);
            }
        }
        return $xml->asXML();
    }
    /**
     * 截取指定长度的字符串(UTF-8专用 汉字和大写字母长度算1，其它字符长度算0.5)
     *
     * @param string $string: 原字符串
     * @param int $length: 截取长度
     * @param string $etc: 省略字符（...）
     * @return string: 截取后的字符串
     */
public static function cut_str($sourcestr, $cutlength = 80, $etc = '......')
    {
        $returnstr = '';
        $i = 0;
        $n = 0.0;
        $str_length = strlen($sourcestr); //字符串的字节数
        while ( ($n<$cutlength) and ($i<$str_length) )
        {
            $temp_str = substr($sourcestr, $i, 1);
            $ascnum = ord($temp_str); //得到字符串中第$i位字符的ASCII码
            if ( $ascnum >= 252) //如果ASCII位高与252
            {
                $returnstr = $returnstr . substr($sourcestr, $i, 6); //根据UTF-8编码规范，将6个连续的字符计为单个字符
                $i = $i + 6; //实际Byte计为6
                $n++; //字串长度计1
            }
            elseif ( $ascnum >= 248 ) //如果ASCII位高与248
            {
                $returnstr = $returnstr . substr($sourcestr, $i, 5); //根据UTF-8编码规范，将5个连续的字符计为单个字符
                $i = $i + 5; //实际Byte计为5
                $n++; //字串长度计1
            }
            elseif ( $ascnum >= 240 ) //如果ASCII位高与240
            {
                $returnstr = $returnstr . substr($sourcestr, $i, 4); //根据UTF-8编码规范，将4个连续的字符计为单个字符
                $i = $i + 4; //实际Byte计为4
                $n++; //字串长度计1
            }
            elseif ( $ascnum >= 224 ) //如果ASCII位高与224
            {
                $returnstr = $returnstr . substr($sourcestr, $i, 3); //根据UTF-8编码规范，将3个连续的字符计为单个字符
                $i = $i + 3 ; //实际Byte计为3
                $n++; //字串长度计1
            }
            elseif ( $ascnum >= 192 ) //如果ASCII位高与192
            {
                $returnstr = $returnstr . substr($sourcestr, $i, 2); //根据UTF-8编码规范，将2个连续的字符计为单个字符
                $i = $i + 2; //实际Byte计为2
                $n++; //字串长度计1
            }
            elseif ( $ascnum>=65 and $ascnum<=90 and $ascnum!=73) //如果是大写字母 I除外
            {
                $returnstr = $returnstr . substr($sourcestr, $i, 1);
                $i = $i + 1; //实际的Byte数仍计1个
                $n++; //但考虑整体美观，大写字母计成一个高位字符
            }
            elseif ( !(array_search($ascnum, array(37, 38, 64, 109 ,119)) === FALSE) ) //%,&,@,m,w 字符按１个字符宽
            {
                $returnstr = $returnstr . substr($sourcestr, $i, 1);
                $i = $i + 1; //实际的Byte数仍计1个
                $n++; //但考虑整体美观，这些字条计成一个高位字符
            }
            else //其他情况下，包括小写字母和半角标点符号
            {
                $returnstr = $returnstr . substr($sourcestr, $i, 1);
                $i = $i + 1; //实际的Byte数计1个
                $n = $n + 0.5; //其余的小写字母和半角标点等与半个高位字符宽...
            }
        }
        if ( $i < $str_length )
        {
            $returnstr = $returnstr . $etc; //超过长度时在尾处加上省略号
        }
        return $returnstr;
    }
    /**
     * 判断是否手机浏览器登陆
     * @return bool
     */
    public static function isMobile()
    {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])) {
            return true;
        }
        //如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset ($_SERVER['HTTP_VIA'])) {
            //找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        }
        //脑残法，判断手机发送的客户端标志,兼容性有待提高
        if (isset ($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = array(
                'nokia',
                'sony',
                'ericsson',
                'mot',
                'samsung',
                'htc',
                'sgh',
                'lg',
                'sharp',
                'sie-',
                'philips',
                'panasonic',
                'alcatel',
                'lenovo',
                'iphone',
                'ipod',
                'blackberry',
                'meizu',
                'android',
                'netfront',
                'symbian',
                'ucweb',
                'windowsce',
                'palm',
                'operamini',
                'operamobi',
                'openwave',
                'nexusone',
                'cldc',
                'midp',
                'wap',
                'mobile'
            );
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i",
                strtolower($_SERVER['HTTP_USER_AGENT']))
            ) {
                return true;
            }
        }
        //协议法，因为有可能不准确，放到最后判断
        if (isset ($_SERVER['HTTP_ACCEPT'])) {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false)
                && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false ||
                    (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') <
                        strpos($_SERVER['HTTP_ACCEPT'],
                            'text/html')))
            ) {
                return true;
            }
        }
        return false;
    }
    /**
     * 文件大小转换 byte ==> kb ==> mb ==> gb
     * @param string $size
     * @param int $prec 保留小数点后位数
     * @return string
     * */
    public static function convertFileSize($size,$prec=0){
        $byte = 1;
        $kb = $byte * 1024;
        $mb = $kb * 1024;
        $gb = $mb * 1024;
        if($size >= $gb){
            $data = round(($size/$gb),$prec)."G";
        }elseif($size >= $mb){
            $data = round(($size/$mb),$prec)."M";
        }elseif ($size >= $kb){
            $data = round(($size/$kb),$prec)."K";
        }else{
            //$data = $size."byte";
            $data = round(($size/$kb),$prec)."K";
        }
        return $data;
    }
    /**
	 * 评分等级星星
     * @param int $num
     * @return string
	 * */
    public static function getStarSymbol($num){
        $data = "";
        for ($i=1; $i<=$num; $i++){
            $data .= "★";
        }
        return $data;
    }
    /**
     * 开启业务
     * @datetime: 2016-4-12 14:13:48
     * @param int $uid
     * @param string $type
     * */
    public static function resourceOpen($uid,$type){
        $model = new MemberResource();
        $data = $model->find("uid=:uid AND type=:type",array(":uid"=>$uid,":type"=>$type));
        if($data){
            if($data['status']==1 && $data['openstatus']!=1){
                    //更新状态
                    $data = MemberResource::model()->find("uid=:uid AND type=:type",array(":uid"=>$uid,":type"=>$type));
                    $data -> openstatus = 1;
                    $data->save();
            }
        }else{
            //新增
            $model -> status = 1;
            $model -> openstatus = 1;
            $model -> uid = $uid;
            $model -> bod = 0;
            $model -> type = $type;
            $model -> key = 0;
            $model -> createtime = time();
            $model -> motifytime = 0;
            $model -> closestart = "0000-00-00";
            $model -> closeend = "0000-00-00";
            if($model -> save()){
                $id = Yii::app()->db->getLastInsertID();
                Common::resourceUpdate($id);
            }
        }
    }
    /*
     * 更新业务状态：开启
     * */
    public static function resourceUpdate($id){
        $data = MemberResource::model()->findByPk($id);
        $data -> key = $id;
        $data->save();
    }
    /**
     * CURL远程读取数据
     * @param string $url
     * @return json
     */
    public static function curlData($url){
        $url = "http://api.921zs.com/".$url;
        //初始化
        $ch = curl_init();
        //设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        //执行并获取HTML文档内容
        $output = curl_exec($ch);
        //释放curl句柄
        curl_close($ch);
        //打印获得的数据
        //print_r($output);
        return $output;
    }
    /*
     * 远程读取数据 支持get和post
     * */
    public static function curlGet921zsData($method,$url,$param=array()){
        $url = "http://api.921zs.com/".$url;
        //初始化
        $ch = curl_init();
        //设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //GET
        if($method == "GET"){
            curl_setopt($ch, CURLOPT_HEADER, 0);
        }
        //POST
        if($method == "POST"){
            $post_data = $param;
            curl_setopt($ch, CURLOPT_POST, 1);
            // post的变量
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        }
        //执行并获取HTML文档内容
        $output = curl_exec($ch);
        //释放curl句柄
        curl_close($ch);
        return $output;
    }
    /*
     * 激活上报
     * @datetime 2016-6-17 13:32:34
     * */
    public static function jhsb(){

    }

    /*
    * 用户类型
     * @param integer $type
     * @return string $type
    * @datetime 2017-3-21 14:32:34
    * */
    public static function getUserType($type){
        switch ($type){
            case 3 :
                $type='ditui';
                break;
            case 4 :
                $type='dealer';
                break;
            case 8:
                $type='newdt';
                break;
            default :
                $type='tongji';
                break;
        }
        return $type;
    }

    /*
     * 截取俩特定字符串之间的
     * @param string $str   特定字符串
     * @param string $start 起始
     * @param string $end  结尾
     * @return string  之间部分
     * @datetime 2017-3-21 14:32:34
     */
    public static  function cutString($start,$end,$str){
        $b= (strpos($str,$start));
        $c= (strpos($str,$end));
        return  substr($str,$b+1,$c-$b-1);
    }

    /**
     * 防止用户登录其他平台
     */
    public static  function preventCrossPlatformLogon(){
        $type_arr = array('member','member','member','ditui','dealer','msg','','','newdt',);
        $type = Yii::app()->user->getState('member_type');

        $url = Yii::app()->request->getUrl();
        $arr =  explode('/',$url);
        if(Yii::app()->user->getState('type') == 'Manage'){
            if ($type_arr[$type]!=$arr[1]){
                $new_url = '/'.$type_arr[$type];
                echo "<meta http-equiv='refresh' content='0;URL=$new_url'>";
            }
        }
    }

    /**
     * 统计分配-----区分代理商跟普通用户
     * @param string $type   统计类型
     * @param Member $member   对象
     */
    public static  function getTongji(Member $member,$type){
        if($member->subagent==707){
            $rompak = RomSoftpak::model()->find('uid=:uid and type=:type and status=1 and (serial_number between 700101 and 700200)',array(':uid'=>0,':type'=>$type));
        }else{
            $rompak = RomSoftpak::model()->find('uid=:uid and type=:type and status=1 and (serial_number not between 700101 and 700200)',array(':uid'=>0,':type'=>$type));
        }
        return $rompak;
    }

    /**
     * 业务流3061
     * 2017-10-16
     * 统计重复记录二次安装
     *zlb 
    */
    public static function repeatInstall($data=array(),$uptype=''){
        Yii::app()->db->createCommand()->insert('app_rom_repeatinstall',
            array(
            // 'id'=>$data->id, 
            'uid'=>$data->uid,
            'type'=>$data->type,
            'imeicode'=>$data->imeicode,
            'simcode'=>$data->simcode,
            'tjcode'=>$data->tjcode,
            'brand'=>$data->brand,
            'status'=>$data->status,
            'model'=>$data->model,
            'finishstatus'=>$data->finishstatus,
            'createtime'=>$data->createtime,
            'closeend'=>$data->closeend,
            'finishdate'=>$data->finishdate,
            'finishtime'=>$data->finishtime,
            'installtime'=>$data->installtime,
            'installcount'=>$data->installcount,
            'ip'=>$data->ip,
            'from'=>$data->from,
            'sys'=>$data->sys,
            'tc'=>$data->tc,
            'tcid'=>$data->tcid,
            'tcfirsttime'=>$data->tcfirsttime,
            'noincome'=>$data->noincome,
            'createstamp'=>$data->createstamp,
            'is_check'=>$data->is_check,
            'uptype'=>$uptype
            )
        );
    }

    /**
     *二次安装，但是这个二次安装与上面的那个不一样，这个uid与第一次安装的不是同一个uid。
     *2017-11-01
     * zlb
     */
    public static function repeatInstall_uid($data=array(),$uptype='',$before_uid=''){
        Yii::app()->db->createCommand()->insert('app_rom_repeatinstall_uid',
            array(
            // 'id'=>$data->id, 
            'uid'=>$data->uid,
            'type'=>$data->type,
            'imeicode'=>$data->imeicode,
            'simcode'=>$data->simcode,
            'tjcode'=>$data->tjcode,
            'brand'=>$data->brand,
            'status'=>$data->status,
            'model'=>$data->model,
            'finishstatus'=>$data->finishstatus,
            'createtime'=>$data->createtime,
            'closeend'=>$data->closeend,
            'finishdate'=>$data->finishdate,
            'finishtime'=>$data->finishtime,
            'installtime'=>$data->installtime,
            'installcount'=>$data->installcount,
            'ip'=>$data->ip,
            'from'=>$data->from,
            'sys'=>$data->sys,
            'tc'=>$data->tc,
            'tcid'=>$data->tcid,
            'tcfirsttime'=>$data->tcfirsttime,
            'noincome'=>$data->noincome,
            'createstamp'=>$data->createstamp,
            'is_check'=>$data->is_check,
            'uptype'=>$uptype,
            'before_uid'=>$before_uid
            )
        );
    }

        /*
       *根据业务类型创建表
       * @param string $type
      * @datetime 2017-3-21 14:32:34
      * */
    public static function createTableByType($type){
        $tablename='app_income_'.$type;
        $sql="DROP TABLE IF EXISTS `{$tablename}`;
            CREATE TABLE `{$tablename}` (
             `uid` int(11) unsigned NOT NULL COMMENT '用户ID',
      `mrid` varchar(20) NOT NULL COMMENT '资源关系ID',
      `data` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT '收益值',
      `createtime` date NOT NULL COMMENT '创建时间',
       `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1可用，0已封号',
      PRIMARY KEY (`uid`,`mrid`,`createtime`),
      KEY `status` (`status`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户资源收益表';";

        $result = Yii::app()->db->createCommand($sql)->execute();
    }

    //创建model
    public static function copyModelByType($type){
        $sourcefile='./protected/models/IncomeBaidu.php';
        $dir='./protected/models/';
        $filename='Income'.ucfirst($type).'.php';

        if( !file_exists($sourcefile)){
            return false;
        }
        return copy($sourcefile, $dir .''. $filename);
    }
    //复制文件后的替换
    public static function fileReplace($type){
        $filename='Income'.ucfirst($type).'.php';
        $url='./protected/models/'.$filename;

        $strContent = file_get_contents($url);//打开文件
        $strContent = str_replace('baidu',$type,$strContent);
        $strContent = str_replace('Baidu',ucfirst($type),$strContent);
        $strContent = str_replace('TYPE_BAIDU','TYPE_'.strtoupper($type),$strContent);
        //$str=str_replace("要替换的内容","替换成的内容",$str);
        file_put_contents($url,$strContent);//把替换的内容写到.php文件中

    }
    //修改ad 文件
    public static function writeFile($type){
        $url='./protected/components/Ad.php';
        $str=Common::getLine($url, 23);
        $str1="const TYPE_UCLLQ= 'ucllq';";
        if(trim($str) == $str1){
            $str2="const TYPE_UCLLQ= 'ucllq';\r\nconst TYPE_".strtoupper($type)."= '".$type."';";
            $strContent = file_get_contents($url);//打开文件
            $strContent = str_replace($str,$str2,$strContent);
            file_put_contents($url,$strContent);//把替换的内容写到.php文件中
        }else{
            ECHO 'error';
        }

    }
    //获取某行字段
    static function getLine($file, $line, $length = 4096){
        $returnTxt = null; // 初始化返回
        $i = 1; // 行数

        $handle = @fopen($file, "r");
        if ($handle) {
            while (!feof($handle)) {
                $buffer = fgets($handle, $length);
                if($line == $i) $returnTxt = $buffer;
                $i++;
            }
            fclose($handle);
        }
        return $returnTxt;
    }
    public static function createByType($type){
        Common::createTableByType($type);
        Common::copyModelByType($type);
        Common::fileReplace($type);
        Common::writeFile($type);
    }

    //不足15位用‘1’补满
    public static function getStrLength($str){
        $str_length = strlen($str); //字符串的字节数
        $lenth=15-$str_length;
        if($lenth<=0){
            $str=$str;
        }else{
            for($i=0;$i<$lenth;$i++){
                $str.='1';
            }
        }
        return $str;
    }
    
        // 根据日期获取下个月月份
    public static function getNextMonth($date){
        $date1 = date('Y-m-01',strtotime($date));
        $date2 = date("Y-m",strtotime("$date1 +1 month"));
        return $date2;
    }

    
    
}