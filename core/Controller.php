<?php
namespace modernphp;

use modernphp\helpers\Inflector;

/**
 * 基础控制器类
 * 所有控制器文件需要继承此类
 */
class Controller extends Object
{
    /**
     * views路径
     */
    private $_viewPath;

    /**
     * 转向视图页
     *
     * @param string $view
     * @param array $params
     * @return void
     */
    public function render($view, $params=[])
    {
        $viewPath=$this->getViewPath();
        $viewFile=$viewPath.DS.$view.'.php';//只支持php文件
        if (!is_file($viewFile)) {
            throw new \Exception("The view file does not exist: $viewFile");
        }
        ob_start();
        ob_implicit_flush(false);
        extract($params, EXTR_OVERWRITE);
        require($viewFile);
        //返回缓冲区内容
        echo ob_get_clean();
    }
    /**
     * 获取视图路径
     *
     * @return void
     */
    public function getViewPath()
    {
        if ($this->_viewPath === null) {
            $this->_viewPath = APP_PATH . DS.'views' . DS . $this->getId();
        }
        return $this->_viewPath;
    }

    /**
     * 设置视图路径
     *
     * @param string $path
     * @return void
     */
    public function setViewPath($path)
    {
        $this->_viewPath = $path;
    }
    
    /**
     * 获取调用控制器的id
     *
     * @return void
     */
    public function getId()
    {
        $controllerId=get_class($this);
        //将驼峰式名称转为以-分隔的名称,以便于视图路径
        return Inflector::camel2id(basename($controllerId));
    }
}
