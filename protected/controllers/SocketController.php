<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/23
 * Time: 16:15
 */
class SocketController extends Controller
{
    public function actionSocket(){
        error_reporting(E_ALL);
        set_time_limit(0);

        $address = '101.201.142.6';
        $port = 10005;
        //创建端口
        if( ($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
            echo "socket_create() failed :reason:" . socket_strerror(socket_last_error()) . "\n";
        }

        //绑定
        if (socket_bind($sock, $address, $port) === false) {
            echo "socket_bind() failed :reason:" . socket_strerror(socket_last_error($sock)) . "\n";
        }

        //监听
        if (socket_listen($sock, 5) === false) {
            echo "socket_bind() failed :reason:" . socket_strerror(socket_last_error($sock)) . "\n";
        }

        do {
            //得到一个链接
            if (($msgsock = socket_accept($sock)) === false) {
                echo "socket_accepty() failed :reason:".socket_strerror(socket_last_error($sock)) . "\n";
                break;
            }
            //welcome  发送到客户端
            $msg = "test000000000000000000";

            socket_write($msgsock, $msg, strlen($msg));
            echo 'read client message\r\n<br/>';
            $buf = socket_read($msgsock, 8192);
            $talkback = "received message:$buf\r\n";
            echo $talkback;
            if (false === socket_write($msgsock, $talkback, strlen($talkback))) {
                echo "socket_write() failed reason:" . socket_strerror(socket_last_error($sock)) ."\n";
            } else {
                echo 'send success';
            }
            //socket_close($msgsock);
        } while(true);
        //关闭socket
        socket_close($sock);
    }
}


