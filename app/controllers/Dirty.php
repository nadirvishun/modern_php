<?php
/**
 * 测试流过滤器
 */
namespace app\controllers;
use modernphp\Controller;
// use app\models\DirtyWordsFilter;//不管用，必须在下方写全

class Dirty extends Controller
{
    /**
     * 测试流方法
     */ 
    public function test()
    {
        //注册过滤器
        stream_filter_register('dirty_words_filter', '\app\models\DirtyWordsFilter');
        //测试过滤
        $data=STORAGE_PATH.DS.'data/data.txt';
        $handle=fopen($data, 'r');
        if($handle){
            stream_filter_append($handle, 'dirty_words_filter');
            //另一种方式
            // $handle=fopen('php://filter/read=dirty_words_filter/resource=data.txt','r');
            while (feof($handle)!==true) {
               $arr[]= fgets($handle);
            }
            fclose($handle);
        }
        //转向app/views/dirty/test.php文件
        $this->render('test',['content'=>$arr]);
    }
}