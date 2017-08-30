<?php
namespace modernphp;

/**
 * 基础类
 */
class Object
{
    /**
     * 构造函数
     *
     * @param array $config
     */
    public function __construct($config=[])
    {
        if (!empty($config)) {
            \Modern::configure($this, $config);
        }
    }
    /**
     * get魔术方法
     *
     * @param string $name
     * @return void
     */
    public function __get($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->$getter();
        }
        throw new \Exception('Getting unknown property: ' . get_class($this) . '::' . $name);
    }
    /**
     * set魔术方法
     *
     * @param string $name
     * @param mix $value
     */
    public function __set($name, $value)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->$setter($value);
            return;
        }
        throw new \Exception('Setting unknown property: ' . get_class($this) . '::' . $name);
    }
}
