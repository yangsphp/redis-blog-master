<?php
/**
 * Created by PhpStorm.
 * User: 25754
 * Date: 2020/1/14
 * Time: 10:11
 */

//$config['socket_type'] = 'tcp'; //`tcp` or `unix`
//$config['socket'] = '/var/run/redis.sock'; // in case of `unix` socket type
$config['host'] = '127.0.0.1';
$config['password'] = NULL;
$config['port'] = 6379;
$config['timeout'] = 0;

//$config = array(
//    'default' => array(
//        'hostname' => '127.0.0.1',
//        'port'     => '6379',
//    ),
//    'redis_slave' => array()
//);
//redis_slave配置如下
//$redis->connect(array('host'=>'127.0.0.1','port'=>6379), true);// master
//$redis->connect(array('host'=>'127.0.0.1','port'=>63791), false);// slave 1
//$redis->connect(array('host'=>'127.0.0.1','port'=>63792), false);// slave 2