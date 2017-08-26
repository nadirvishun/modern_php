<?php
/**
 * 测试流过滤器
 */
namespace modernphp\app\controllers;

// use modernphp\app\models\DirtyWordsFilter;//不管用，不需在下方写全

class Dirty
{
    public function test()
    {
        //注册过滤器
        stream_filter_register('dirty_words_filter', '\modernphp\app\models\DirtyWordsFilter');
        //测试过滤
        $data=STORAGE_PATH.DS.'data/data.txt';
        $handle=fopen($data, 'r');
        if($handle){
            stream_filter_append($handle, 'dirty_words_filter');
            //另一种方式
            // $handle=fopen('php://filter/read=dirty_words_filter/resource=data.txt','r');
            while (feof($handle)!==true) {
                echo fgets($handle).PHP_EOL;
            }
            fclose($handle);
        }
    }
}