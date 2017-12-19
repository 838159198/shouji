<?php
use Workerman\Worker;
use Workerman\Lib\Timer;
require_once __DIR__ . '/Workerman/Autoloader.php';
// 初始化一个worker容器，监听1234端口
$worker = new Worker('tcp://47.93.85.18:10006');

/*
 * 注意这里进程数必须设置为1，否则会报端口占用错误
 * (php 7可以设置进程数大于1，前提是$inner_text_worker->reusePort=true)
 */
// 全局数组保存uid在线数据
$uidConnectionMap = array();
$aaa=array();//保存发送info 后受到的包
$heartCheck=array();//保存最近一次接收到的working 时间
$loginTime=array();//保存第一次链接时间
$worker->count = 1;
// 新增加一个属性，用来保存uid到connection的映射
$worker->uidConnections = array();
// worker进程启动后创建一个text Worker以便打开一个内部通讯端口
$worker->onWorkerStart = function($worker)
{
    // 开启一个内部端口，方便内部系统推送数据，Text协议格式 文本+换行符
    $inner_text_worker = new Worker('websocket://47.93.85.18:5678');
    $inner_text_worker->onMessage = function($connection, $buffer)
    {
        // $data数组格式，里面有uid，表示向那个uid的页面推送数据
        $data = json_decode($buffer, true);
        $uid = $data['uid'];
        // 通过workerman，向uid的页面推送数据
        $ret = sendMessageByUid($uid, $buffer);
        // 返回推送结果
        global $aaa,$uidConnectionMap,$heartCheck,$loginTime;

        $connection->send(json_encode(array("uidConnectionMap"=>array_unique($uidConnectionMap),"heartCheck"=>$heartCheck,"aaa"=>$aaa,"loginTime"=>$loginTime)));
    };

    // ## 执行监听 ##
    $inner_text_worker->listen();


    // 监听一个http端口
    $inner_http_worker = new Worker('http://47.93.85.18:21212');
    // 当http客户端发来数据时触发
    $inner_http_worker->onMessage = function($http_connection, $data){
        global $uidConnectionMap,$aaa;
        $_POST = $_POST ? $_POST : $_GET;
        // 推送数据的url格式 type=publish&to=uid&content=xxxx
        switch(@$_POST['type']){
            case 'publish':
                global $worker;
                $to = @$_POST['to'];
                $_POST['content'] = htmlspecialchars(@$_POST['content']);
                // 有指定uid则向uid所在socket组发送数据
                if($to){
                    sendMessageByUid($to,$_POST['content']);
                    //$worker->to($to)->emit('new_msg', $_POST['content']);
                    // 否则向所有uid推送数据
                }else{
                    broadcast(@$_POST['content'],@$_POST['uids']);
                    //$worker->emit('new_msg', @$_POST['content']);
                }
                // http接口返回，如果用户离线socket返回fail
                if($to && !in_array($to,$uidConnectionMap)){
                    return $http_connection->send('offline');
                }else{
                    if($_POST['content']=='info'){
                        return $http_connection->send('ok');
                    }else{
                        return $http_connection->send('ok');
                    }

                }
        }
        return $http_connection->send('fail');
    };
    // 执行监听
    $inner_http_worker->listen();
};
/*$worker->onConnect = function ($connection) {
    // 每10s 检查客户端是否有name属性
    Timer::add(5, function () use ($connection) {
        global $uidConnectionMap;
        if (!isset($connection->uid)) {
            foreach($uidConnectionMap as $k=>$v){
                if($v==$connection->uid)
                    unset($uidConnectionMap[$k]);
            }
            //$connection->close("auth timeout and close");
        }

    }, null, false);
};*/

// 当有客户端发来消息时执行的回调函数
$worker->onMessage = function($connection, $data)
{var_dump($data);
    global $aaa,$uidConnectionMap,$worker,$heartCheck,$loginTime;

    if($data=='working'){
        $heartCheck[$connection->uid]=time();
        return;
    }
    if(strstr($data,'id:')){
        $route_id=substr($data,3);
        if(!in_array($route_id,$uidConnectionMap)){
            $uidConnectionMap[]=$route_id;
        }
    }elseif(strstr($data,'info:')){
        $aaa[$connection->uid]=substr($data,5);
    }
    // 判断当前客户端是否已经验证,既是否设置了uid
    if(!isset($connection->uid) && strstr($data,'id:'))
    {
        // 没验证的话把第一个包当做uid（这里为了方便演示，没做真正的验证）
        $connection->uid = substr($data,3);
        /* 保存uid到connection的映射，这样可以方便的通过uid查找connection，
         * 实现针对特定uid推送数据
         */
        $worker->uidConnections[$connection->uid] = $connection;
        $loginTime[$connection->uid]=time();
        return;
    }
    //心跳判断
    if(isset($heartCheck[$connection->uid])){
        $time=$heartCheck[$connection->uid];
        $time2=time();
        if($time2-$time>120){
            foreach($uidConnectionMap as $k=>$v){
                if($v==$connection->uid)
                    unset($uidConnectionMap[$k]);
                return;
            }
        }
    }
    //$connection->send($aaa);
};


// 向所有验证的用户推送数据
function broadcast($message,$uids)
{
    global $worker;
    foreach($worker->uidConnections as $connection)
    {
        if(in_array($connection->uid,explode(',',$uids))){
            $connection->send($message);
        }
    }
}

// 针对uid推送数据
function sendMessageByUid($uid, $message)
{
    global $worker;
    if(isset($worker->uidConnections[$uid]))
    {
        $connection = $worker->uidConnections[$uid];
        $connection->send($message);
        return true;
    }
    return false;
}

// 运行所有的worker
Worker::runAll();