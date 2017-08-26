<?php
//自动加载
require dirname(__DIR__).'/vendor/autoload.php';

use modernphp\url\Scanner;
use League\Csv\Reader;


// ob_start();
$urls=[
    'http://www.apple.com',
    'http://php.net',
    'http://sdfssdwerw.org'
];
$scanner=new Scanner($urls);
print_r($scanner->getInvalidUrls());
// ob_flush();

$file=__DIR__.DIRECTORY_SEPARATOR.'url.csv';
$reader=Reader::createFromPath($file);
$csv=$reader->fetchAll();
$urls=[];
foreach ($csv as $csvRow) {
    $urls[]=$csvRow[0];
}
$scanner=new Scanner($urls);
print_r($scanner->getInvalidUrls());
// $abc=ob_get_contents();
// ob_flush();
// echo $abc;


/* //实例化guzzle
$client=new Client();
//处理csv
$file=__DIR__.DIRECTORY_SEPARATOR.'url.csv';
// $csv=new Reader($argv[1]);//用法有点旧了
$reader=Reader::createFromPath($file);
$csv=$reader->fetchAll();
foreach ($csv as $csvRow) {
    try {
        //options请求
        $httpResponse=$client->options($csvRow[0]);
        if ($httpResponse->getStatusCode()>=400) {
            throw new \Exception();
        }
    } catch (\Exception $e) {
        echo $csvRow[0].PHP_EOL;
    }
} */
