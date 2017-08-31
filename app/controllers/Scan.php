<?php
/**
 * 测试网址是否有效
 */
namespace app\controllers;

use modernphp\Controller;
use app\models\Scanner;
use League\Csv\Reader;

class Scan extends Controller
{
    /**
     * 测试有效网址
     *
     * @return void
     */
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
        $invalidUlrs=$scanner->getInvalidUrls();
        $this->render('test',['invalidUlrs'=>$invalidUlrs]);
    }
}
