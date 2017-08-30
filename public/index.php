<?php
//定义环境
defined("ENVIRONMENT") or define("ENVIRONMENT", 'pro');//'pro' or 'dev'

//自动加载
require __DIR__.'/../vendor/autoload.php';

//运行应用
require(__DIR__.'/../core/Modern.php');
$config=require(CONFIG_PATH . DS.'main.php');
(new \modernphp\Application($config))->run();
