<?php

//redis实现点赞数
//
$redis = new Redis(); //实例化redis
$key='liu';$value='123';
$redis->pconnect('127.0.0.1', '6379'); //建立redis服务连接    这个pconnect应该是长连接，可以用connect
$redis->set($key, $value); //设置变量和变量值
echo $redis->get($key); //获取变量值
$redis->close(); //关闭redis连接