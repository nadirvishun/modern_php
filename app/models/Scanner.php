<?php
/**
 * 测试url是否有效
 */
namespace app\models;

use GuzzleHttp\Client;

class Scanner
{
    protected $urls;
    protected $httpClient;

    public function __construct(array $urls)
    {
        $this->urls=$urls;
        $this->httpClient=new Client();
    }
    /**
     * 获取死链
     */
    public function getInvalidUrls()
    {
        $invalidUrls=[];
        foreach ($this->urls as $url) {
            try {
                $statusCode=$this->getStatusCodeForUrl($url);
            } catch (\Exception $e) {
                $statusCode=500;
            }
            if ($statusCode>=400) {
                array_push($invalidUrls, [
                    'url'=>$url,
                    'status'=>$statusCode
                ]);
            }
        }
        return $invalidUrls;
    }
    /**
     * 获取状态码
     */
    protected function getStatusCodeForUrl($url)
    {
        $httpResponse=$this->httpClient->options($url);
        return $httpResponse->getStatusCode();
    }
}
