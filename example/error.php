<?php
//自动加载
require dirname(__DIR__) . '/vendor/autoload.php';

use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Handler\JsonResponseHandler;
use Monolog\Logger;
use Monolog\ErrorHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SwiftMailerHandler;

// define('ENVIRONMENT', 'dev');
define('ENVIRONMENT', 'pro');

if (ENVIRONMENT == 'dev') {
    /**
     * 在测试环境中美化展示错误
     */
    //引入美化错误展示
    $whoops = new Run();
    $handler = new PrettyPageHandler();
    //可以点击网页中错误信息中地址从而跳转vscode打开
    $handler->setEditor('vscode');
    $whoops->pushHandler($handler);
    // $whoops->pushHandler(new JsonResponseHandler);
    $whoops->register();
}
/**
* 在生产环境纪录日志
*/
if (ENVIRONMENT == 'pro') {
    //用whoops来通过不同的hander来处理
    // $whoops = new Run();
    // $whoops->pushHandler(function($exception, $inspector, $run){
    //     echo $exception->getMessage();
    // });
    // $whoops->register();

    //用monolog来记录
    $log = new Logger('app');
    $path = __DIR__ . DIRECTORY_SEPARATOR . 'log.txt';
    $handler = new StreamHandler($path, Logger::NOTICE);
    $log->pushHandler($handler);
    ErrorHandler::register($log);//接管纪录错误和异常

    //whoops和monolog一起用（但是纪录的exception需要额外判断是notice还是warning还是其它）
    // $whoops = new Run();
    // $whoops->pushHandler(function($exception, $inspector, $run) use($log){
    //     $log->warning($exception->getMessage());
    // });
    // $whoops->register();
    
    //monolog通过swiftmailer发送邮件
    date_default_timezone_set('PRC');//设置时间
    //配置stmp
    $transport = (new \Swift_SmtpTransport('smtp.163.com', 25))
    ->setUsername('xxx@163.com')
    ->setPassword('xxxx');
    //创建mailer
    $mailer = new \Swift_Mailer($transport);
    //创建发送信息
    $message = (new \Swift_Message('Wonderful Subject'))
    ->setSubject('Website Error')
    ->setFrom(['xxx.com' => 'vishun'])
    ->setTo(['xxx@qq.com']);
    //注册swiftmailer句柄
    $mailLog=new Logger('app.db');
    $mailLog->pushHandler(new SwiftMailerHandler($mailer,$message,Logger::ERROR));
    //测试
    $mailLog->error('test send email!');
}
//测试错误显示
// error_reporting(E_ALL ^ E_NOTICE);
// $a=1;
// foreach ($a as $v) {
// }
throw new Exception('123');

