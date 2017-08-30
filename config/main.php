<?php
return [
    //时区
    'timeZone'=>'PRC',
    'components' => [
        //monolog配置，默认是RotatingFile处理
        'logger'=>[
            'name' => 'app',
            'path' => __DIR__ . '/../storage/logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
            'maxFiles'=>10
        ],
        //swiftmailer配置，默认是smtp
        'mailer'=>[
            'host'=>'smtp.163.com',
            'port'=>25,
            'username'=>'xxx@163.com',
            'password'=>'xxx'
        ]
    ]
];
