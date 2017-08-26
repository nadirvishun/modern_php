<?php
//定义环境
define('ENVIRONMENT', 'pro');//'pro' or 'dev'
//定义分隔路径缩写
defined("DS") or define("DS", DIRECTORY_SEPARATOR);
//定义根目录
defined("MODERN_PATH") or define("MODERN_PATH", dirname(__DIR__));
//定义Public路径
defined("PUBLIC_PATH") or define("PUBLIC_PATH", MODERN_PATH.DS.'public');
//定义App文件路径
defined("APP_PATH") or define("APP_PATH", MODERN_PATH.DS.'app');
//定义Storage文件路径
defined("STORAGE_PATH") or define("STORAGE_PATH", MODERN_PATH.DS.'storage');

//设置时间
date_default_timezone_set('PRC');

//自动加载
require MODERN_PATH.DS.'vendor/autoload.php';

//日志相关
//普通的文件纪录日志
$log = new \Monolog\Logger('app');
$path = STORAGE_PATH .DS.'logs'.DS. date('Y.m.d').'.log';
$handler = new \Monolog\Handler\StreamHandler($path);
$log->pushHandler($handler);
//发送邮件日志
//配置stmp
$transport = (new \Swift_SmtpTransport('smtp.163.com', 25))
 ->setUsername('xxx@163.com')
 ->setPassword('xxx');
//创建mailer
$mailer = new \Swift_Mailer($transport);
//创建发送信息
$message = (new \Swift_Message('Wonderful Subject'))
 ->setSubject('Website Error')
 ->setFrom(['xxx@163.com' => 'vishun'])
 ->setTo(['xxxx@qq.com']);
//注册swiftmailer句柄(只有app.db分类，且级别为error的才发送邮件)
$mailLog=new \Monolog\Logger('app.db');
$mailLog->pushHandler(new \Monolog\Handler\SwiftMailerHandler($mailer, $message, \Monolog\Logger::ERROR));
//测试发送邮件日志
// $mailLog->error('test app.db error');
//测试普通日志
//$log->error('test app error');

//错误异常处理
if (ENVIRONMENT == 'dev') {//如果是开发环境
    $whoops = new \Whoops\Run();
    $handler = new \Whoops\Handler\PrettyPageHandler();//引入美化展示handler
    $handler->setEditor('vscode');//可以点击网页中错误信息中地址从而跳转vscode打开
    $whoops->pushHandler($handler);
    $whoops->register();
} elseif (ENVIRONMENT == 'pro') {//如果是生产环境
    //可以用monolog来接管错误和异常，但monolog没有将错误转为异常来处理,纪录错误后还是会在前台展示错误相关信息
    // \Monolog\ErrorHandler::register($log);//接管纪录错误和异常
    //所以虽然Whoops官方不建议在生产环境使用，但也可以使用
    $whoops = new \Whoops\Run();
    $whoops->pushHandler(function ($exception, $inspector, $run) use ($log) {
        //增加名称为app.exception，以区分开来
        $log->withName('app.exception')->error($exception->getMessage());//纪录日志（发现laravel和yii2等都将此纪录的等级设为error，没有再细分）
        //将出错前的内容清除掉，只显示出错内容
        for ($level = ob_get_level(); $level > 0; --$level) {
            if (!@ob_end_clean()) {
                ob_clean();
            }
        }
        echo '-_-!!,悲剧了!';//展示自定义错误信息而不暴露详细信息
    });
    $whoops->register();
}

//引入路由
require '../routes/web.php';
