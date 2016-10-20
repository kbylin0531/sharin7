<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 16-10-20
 * Time: 下午2:55
 */
require __DIR__.'/../../../Sharin/unitest.inc';
$dbname = null;//使用默认的名称

//测试 创建数据库和表
\Sharin\dump('CHECK INSTALLED:',\Sharin\Library\SQLite::isInstalled($dbname));
try{
    \Sharin\dump('DO INSTALL:',\Sharin\Library\SQLite::install($dbname));
}catch (\Sharin\Exception $e) {
    \Sharin\dump('ERROR:',$e->getMessage());
}


//创建
//命令行模式下无法使用
// 安装： apt-cache search pdo-sqlite
//      apt-get install php7.0-sqlite3
$instance = \Sharin\Library\SQLite::getInstance($dbname);
\Sharin\dump(
    $instance->create([
            'ID'   => time(),
            'NAME' => 'dadsadsa'
        ]),
    $instance->lastInsertId(),//不一定有效
    $instance->select(),
    $instance->select(' id = 1231'),
    $instance->find(1231)
);