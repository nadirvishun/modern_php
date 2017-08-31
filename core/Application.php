<?php
namespace modernphp;

use FastRoute\Dispatcher;

/**
 * 初始引导类
 */
class Application extends Object
{
    private $_logger;
    private $_mailer;
    private $_components;
    
    /**
     * 构造函数
     *
     * @param array $config
     */
    public function __construct($config=[])
    {
        \Modern::$app=$this;
        $this->init($config);
        // $this->registerErrorHandler();
        parent::__construct($config);
    }
    /**
     * 初始化相关配置
     *
     * @param array $config 
     * @return void
     */
    public function init(&$config)
    {
        //如果参数中有时区
        if (isset($config['timeZone'])) {
            date_default_timezone_set($config['timeZone']);
            //由于是引用传值，去掉此配置参数，以免影响后续的components赋值
            unset($config['timeZone']);
        } elseif (!ini_get('date.timezone')) {//如果没有且没有在php.ini设置，则设置默认
            date_default_timezone_set('PRC');
        }
    }

    /**
     * 将配置文件存储起来
     *
     * @param array $config
     * @return void
     */
    public function setComponents($config)
    {
        $this->_components=$config;
    }

    /**
     * 获取组件的配置
     *
     * @return void
     */
    public function getComponents()
    {
        return $this->_components;
    }
    
    /**
     * 获取日志实例
     *
     * @return void
     */
    public function getLogger()
    {
        if ($this->_logger !== null) {
            return $this->_logger;
        }
        $name=isset($this->components['logger']['name'])?$this->components['logger']['name']:'app';//日志名称
        $path=isset($this->components['logger']['path'])?$this->components['logger']['path']:STORAGE_PATH .DS.'logs'.DS. 'app.log';//日志路径
        $level=isset($this->components['logger']['level'])?$this->components['logger']['level']:\Monolog\Logger::DEBUG;//日志等级
        $maxFiles=isset($this->components['logger']['maxFiles'])?$this->components['logger']['maxFiles']:10;//最多保持日志个数

        $logger = new \Monolog\Logger($name);
        $handler = new \Monolog\Handler\RotatingFileHandler($path, $maxFiles);//每日
        $logger->pushHandler($handler);
        $logger->pushProcessor(new \Monolog\Processor\WebProcessor());
        return $this->_logger =$logger;
    }

     /**
     * 获取邮件实例
     *
     * @return void
     */
    public function getMailer()
    {
        if ($this->_mailer !== null) {
            return $this->_mailer;
        }
        $host=$this->components['mailer']['host'];
        $port=$this->components['mailer']['port'];
        $username=$this->components['mailer']['username'];
        $password=$this->components['mailer']['password'];

        $transport = (new \Swift_SmtpTransport($host, $port))
        ->setUsername($username)
        ->setPassword($password);

        return $this->_mailer = new \Swift_Mailer($transport);
    }
    /**
     * 注册错误处理
     *
     * @return void
     */
    public function registerErrorHandler()
    {
        $whoops = new \Whoops\Run();
        if (ENVIRONMENT === 'dev') {//如果是开发环境
            $handler = new \Whoops\Handler\PrettyPageHandler();//引入美化展示handler
            $handler->setEditor('vscode');//可以点击网页中错误信息中地址从而跳转vscode打开
            $whoops->pushHandler($handler);
        } elseif (ENVIRONMENT === 'pro') {//如果是生产环境
            $whoops->pushHandler(function ($exception, $inspector, $run) {
                //增加名称为app.exception，以区分开来
                $this->logger->withName('app.exception')->error($exception->getMessage());//纪录日志（发现laravel和yii2等都将此纪录的等级设为error，没有再细分）
                $this->clearOutput();
                //TODO,指向错误视图页
                echo '-_-!!,悲剧了!';//展示自定义错误信息而不暴露详细信息
            });
        }
        $whoops->register();
    }
    /**
     * 运行
     *
     * @return void
     */
    public function run()
    {
        //引入路由
        $collector=require MODERN_PATH.'/routes/web.php';
        $this->dispatch($collector);
    }
    /**
     * 路由解析分发
     *
     * @param callable $callBack
     * @return void
     */
    public function dispatch(callable $callBack)
    {
        $dispatcher = \FastRoute\simpleDispatcher($callBack);

        // 获取方法和请求url
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];
        
        // 如果url中有？则将？后面的去掉，
        //例如http://www.xxx.com/abc?foo=123,则转为http://www.xxx.com/abc
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);
        //必须隐藏index.php才能正确解析出来
        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND://如果是没有找到路由
                // ... 404 Not Found(直接抛出异常)
                throw new \Exception('404 Not Found');
                break;
            case Dispatcher::METHOD_NOT_ALLOWED://如果是有路由，但方法不对
                $allowedMethods = $routeInfo[1];
                // ... 405 Method Not Allowed(直接抛出异常)
                throw new \Exception('Method Not Allowed');
                break;
            case Dispatcher::FOUND://找到
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                //分为两种情况，一种是闭包一种是指向controller
                if ($handler instanceof \Closure) {//如果是闭包
                    $handler();
                } else {//如果是指向controller
                    list($class, $method) = explode("@", $handler, 2);
                    call_user_func_array(array(new $class, $method), $vars);
                }
                break;
        }
    }
    /**
     * 清除缓存区内容
     *
     * @return void
     */
    public function clearOutput()
    {
        for ($level = ob_get_level(); $level > 0; --$level) {
            if (!@ob_end_clean()) {
                ob_clean();
            }
        }
    }
}
