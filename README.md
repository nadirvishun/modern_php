# modernphp
在1.0版本中仅仅是写了一下几个单独的类，然后发现和普通的框架就差一个路由了（雾），所以引入了一个路由组件FastRouter：
```
|-app
    |-controllers //控制器所在
    |-models      //模型所在
|-pulbic          //网站根目录，入口文件所在
|-routes          //路由文件所在
|-storage         //日志存储所在等
|-tests           //单元测试所在等
```

错误接管及日志：修改public/index.php中常量ENVIRONMENT为'dev'(开发环境，美化显示)或’pro‘（生产环境，纪录日志）

访问流过滤器：`http://xxxx.com/dirty/test`

访问网址有效：`http://xxxx.com/scan/test`