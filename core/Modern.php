<?php

//定义分隔路径缩写
defined("DS") or define("DS", DIRECTORY_SEPARATOR);
//定义根目录
defined("MODERN_PATH") or define("MODERN_PATH", dirname(__DIR__));
//定义Public路径
defined("PUBLIC_PATH") or define("PUBLIC_PATH", MODERN_PATH.DS.'public');
//定义App文件路径
defined("APP_PATH") or define("APP_PATH", MODERN_PATH.DS.'app');
//定义Storage文件路径
defined("STORAGE_PATH") or define("STORAGE_PATH", MODERN_PATH.DS.'storage');
//定义Config文件路径
defined("CONFIG_PATH") or define("CONFIG_PATH", MODERN_PATH.DS.'config');
//定义是否接管日志
defined('ENABLE_ERROR_HANDLER') or define('ENABLE_ERROR_HANDLER', true);

class Modern
{
    /**
     * Application实例
     */
    public static $app;

    /**
     * 版本
     *
     * @return void
     */
    public static function getVersion()
    {
        return '1.2.1';
    }
    /**
     * 解析配置文件
     *
     * @param object $object
     * @param array $properties
     * @return void
     */
    public static function configure($object, $properties)
    {
        foreach ($properties as $name => $value) {
            $object->$name = $value;
        }

        return $object;
    }
}
