<?php
/**
 * 文字过滤器
 */
class DirtyWordsFilter extends php_user_filter
{
    /**
     * 过滤实现
     * @param resource $in 入桶队列数据
     * @param resource $out 出桶队列数据
     * @param int $consumed 处理的字节数
     * @param bool $closing
     * @return void
     */
    public function filter($in, $out, &$consumed, $closing)
    {
        //定义需要过滤的词
        $words=['grime','dirty','greese'];
        //存储数组
        $wordData=[];
        foreach ($words as $word) {
            //需要填充替换的字符
            $replacement=array_fill(0, mb_strlen($word), '*');
            $wordData[$word]=implode('', $replacement);
        }
        //需要过滤的数组
        $bad=array_keys($wordData);
        //要替换的数组
        $good=array_values($wordData);
        while ($bucket=stream_bucket_make_writeable($in)) {
            //替换非法字符
            $bucket->data=str_replace($bad, $good, $bucket->data);
            //计数
            $consumed+=$bucket->datalen;
            //追加到桶队列中
            stream_bucket_append($out, $bucket);
        }
        //成功且有数据返回的常量
        return PSFS_PASS_ON;
    }
}
//注册过滤器
stream_filter_register('dirty_words_filter', 'DirtyWordsFilter');
//测试过滤
$handle=fopen('data.txt', 'r');
stream_filter_append($handle,'dirty_words_filter');
//另一种方式
// $handle=fopen('php://filter/read=dirty_words_filter/resource=data.txt','r');
while (feof($handle)!==true) {
    echo fgets($handle);
}
fclose($handle);

