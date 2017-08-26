<?php
/**
 * 测试网址是否有效
 */
namespace modernphp\app\controllers;

use modernphp\app\models\Scanner;
use League\Csv\Reader;

class Scan
{
    public function test()
    {
        // $urls=[
        //     'http://www.apple.com',
        //     'http://php.net',
        //     'http://sdfssdwerw.org'
        // ];
        // $scanner=new Scanner($urls);
        // print_r($scanner->getInvalidUrls());

        $file=STORAGE_PATH.DS.'data/url.csv';
        $reader=Reader::createFromPath($file);
        $csv=$reader->fetchAll();
        $urls=[];
        foreach ($csv as $csvRow) {
            $urls[]=$csvRow[0];
        }
        $scanner=new Scanner($urls);
        print_r($scanner->getInvalidUrls());
    }
}
