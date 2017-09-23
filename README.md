# modernphp
1.0版本虽然有初步额mvc模型，但启动过程非常杂乱，所以在1.1版本上进一步封装：
- 引入统一的Application类来处理引导启动
- 引入配置文件，可以进行一定程度的组件配置
- 引入views视图展示
- 修改命名空间方式，cotroller和model放在app命名空间下：
  ```
    "autoload": {
        "psr-4": {
            "modernphp\\": "core/",
            "app\\": "app/"
        }
    },
  ```

当前目录结构：
```
|-app
    |-controllers //控制器所在
    |-models      //模型所在
    |-views       //视图文件所在
|-config          //配置文件所在
|-core            //引导文件所在
|-pulbic          //网站根目录，入口文件所在
|-routes          //路由文件所在
|-storage         //储所在等
    |-logs        //日志纪录文件所在
|-tests           //单元测试所在等
```

错误接管及日志：修改public/index.php中常量ENVIRONMENT为'dev'(开发环境，美化显示)或’pro‘（生产环境，纪录日志）

访问流过滤器：`http://xxxx.com/dirty/test`

访问网址有效：`http://xxxx.com/scan/test`

离真正的框架还差老远老远，还缺少的主要组件有：
- DI依赖注入组件
- Request请求组件
- Response响应组件
- Db数据库组件
- Cache缓存组件
- Session/Cookie组件
- Cli相关命令行操作

上方仅仅是主要的，还有其它各种各样的，更别说组件本身各种细节的实现和整合，所以说要想写一个框架真是超级麻烦。

## TODO
- 路由参照lumen进一步封装，引入统一命名空间配置等