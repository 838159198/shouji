<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/16
 * Time: 16:06
 */
//error_reporting(E_ALL);
echo "<h2>tcp/ip connection </h2>\n";
$service_port = 10006;
$address = '101.201.142.6';

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === false) {
    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
} else {
    echo "OK. <br>";
}

echo "Attempting to connect to '$address' on port '$service_port'...<br>";
$result = socket_connect($socket, $address, $service_port);
if($result === false) {
    echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
} else {
    echo "sucess <br>";
}
$in=time();
$out = "";
echo "sending http head request ...<br>";
socket_write($socket, $in, strlen($in));

while ($out = socket_read($socket, 8192)) {
    echo $out;
}
echo "closeing socket..<br>";
socket_close($socket);
echo "ok <br>";