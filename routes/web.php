<?php
/**
 * fastRoute路由
 * 还可以继续细分，同时引入命名空间的分组等，可参考lumen框架中的处理，这里不做进一步处理了
 */
return function (FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/users', function () {//测试匿名函数
        echo '123';
    });
    //不支持设置统一的命名空间,暂时只能写全路径
    $r->addRoute('GET', '/dirty/test', '\app\controllers\Dirty@test');//测试流相关
    $r->get('/scan/test', '\app\controllers\Scan@test');//测试url是否有效相关
    $r->get('/logger/testmail', '\app\controllers\Logger@testMail');
};
