<?php
use modernphp\Whovian;

class WhovianTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * 测试构造函数
     *
     * @return void
     */
    public function testSetsDoctorWithConstructor()
    {
        $whovian=new Whovian('Some Body');
        $this->assertAttributeEquals('Some Body','favoriteDoctor',$whovian);//内部经过反射来获取
        // $this->assertEquals('Some Body',$whovian->favoriteDoctor);//由于属性为protected，所以不行
    }
    /**
     * 测试say()方法
     *
     * @return void
     */
    public function testSaysDoctorName(){
        $whovian=new Whovian('Some Body');
        $this->assertEquals('The beast doctor is Some Body',$whovian->say());
    }
    /**
     * 测试成功的respondTo()方法
     *
     * @return void
     */
    public function testRespondToInAgreement(){
        $whovian=new Whovian('Some Body');
        $opinion='Some Body balabala';
        $this->assertEquals('I agree!',$whovian->respondTo($opinion));
    }
    /**
     * 测试反对的respondTo()方法
     * @expectedException Exception
     * @return void
     */
    public function testRespondToInDisagreement(){
        $whovian=new Whovian('Some Body');
        $opinion='balabala';
        $whovian->respondTo($opinion);//注意上方的注释，看是否抛出异常，如果抛出则通过
    }
}