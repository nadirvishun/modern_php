<?php
namespace app\controllers;

use modernphp\Controller;
use Modern;

/**
 * 测试日志相关
 */
class Logger extends Controller
{
    /**
     * 测试日志错误邮件发送
     * 需要先将config/mail.php中mailer相关参数配置好才行
     * @return void
     */
    public function testMail()
    {
        //获取日志实例
        $logger=Modern::$app->logger;
        //获取mail实例
        $mailer=Modern::$app->mailer;
        //copy专用的邮件日志实例
        $mailLog=$logger->withName('app.db');
        //设置邮件信息
        $message = (new \Swift_Message('Wonderful Subject'))
        ->setSubject('Website Error')
        ->setFrom(['xxx@163.com' => 'vishun'])
        ->setTo(['xxx@qq.com']);
        //注册邮件handler
        $mailLog->pushHandler(new \Monolog\Handler\SwiftMailerHandler($mailer, $message, \Monolog\Logger::ERROR));

        //测试邮件发送,实际测试成功，但是好繁琐，后续优化
        $mailLog->error('邮件发送');
    }
}
