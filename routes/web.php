<?php
/**
 * fastRoute路由
 */
$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/users', function () {//测试匿名函数
        echo '123';
    });
    //貌似不支持设置统一的命名空间,暂时只能写全路径？
    $r->addRoute('GET', '/dirty/test', '\modernphp\app\controllers\Dirty/test');//测试流相关
    $r->addRoute('GET', '/scan/test', '\modernphp\app\controllers\Scan/test');//测试url是否有效相关
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);
//必须隐藏index.php才能正确解析出来
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND://如果是没有找到路由
        // ... 404 Not Found(直接抛出异常)
        throw new Exception('404 Not Found');
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED://如果是有路由，但方法不对
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed(直接抛出异常)
        throw new Exception('Method Not Allowed');
        break;
    case FastRoute\Dispatcher::FOUND://找到
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        //分为两种情况，一种是闭包一种是指向controller
        if ($handler instanceof Closure) {//如果是闭包
            $handler();
        } else {//如果是指向controller
            list($class, $method) = explode("/", $handler, 2);
            call_user_func_array(array(new $class, $method), $vars);
        }
        break;
}
