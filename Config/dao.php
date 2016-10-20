<?php
/**
 * Created by PhpStorm.
 * User: asus
 * Date: 16-10-18
 * Time: 下午2:13
 */
return [
    DRIVER_CLASS_LIST   => [
        'Sharin\\Core\\Dao\\MySQL',
    ],
    DRIVER_CONFIG_LIST  => [
        [
            'dbname'    => 'sharin',//选择的数据库
            'username'  => 'lin',
            'password'  => '123456',
            'host'      => '127.0.0.1',
            'port'      => '3306',
            'charset'   => 'UTF8',
            'dsn'       => null,//默认先检查差DSN是否正确,直接写dsn而不设置其他的参数可以提高效率，也可以避免潜在的bug
//            'options'   => [
//                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,//结果集返回形式
//            ],
        ],
    ],
];