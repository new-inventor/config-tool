<?php

/**
 * User: Ionov George
 * Date: 01.04.2016
 * Time: 15:14
 */

use NewInventor\ConfigTool\Helper\ArrayHelper;

class ArrayHelperTest extends PHPUnit_Framework_TestCase
{
    private $arr;

    public function setUp()
    {
        $this->arr = [
            'qwe',
            'asd' => '123',
            'zxc' => [
                'qwe' => '456',
                456 => '000'
            ]
        ];
    }

    public function testGet()
    {
        $this->assertEquals('qwe', ArrayHelper::get($this->arr, 0));
        $this->assertEquals('123', ArrayHelper::get($this->arr, 'asd'));
        $this->assertEquals('000', ArrayHelper::get($this->arr, ['zxc', 456]));
        $this->assertEquals('111', ArrayHelper::get($this->arr, ['zxc', 0], '111'));
    }

    public function testSet()
    {
        ArrayHelper::set($this->arr, 'asd', 1);
        $this->assertEquals(1, ArrayHelper::get($this->arr, 'asd'));
        ArrayHelper::set($this->arr, ['asd'], 100);
        $this->assertEquals(100, ArrayHelper::get($this->arr, 'asd'));
        ArrayHelper::set($this->arr, ['zxc', 0, '123'], '1sdasd');
        $this->assertEquals('1sdasd', ArrayHelper::get($this->arr, ['zxc', 0, '123']));

        $this->setExpectedException('NewInventor\ConfigTool\Exception\SetException');
        ArrayHelper::set($this->arr, [0, 'asd'], 1);
    }

    public function testCheckRout()
    {
        $this->assertTrue(ArrayHelper::checkRoute('asd'));
        $this->assertTrue(ArrayHelper::checkRoute(['asd']));
        $this->assertTrue(ArrayHelper::checkRoute(['asd', 0]));
        $this->assertTrue(ArrayHelper::checkRoute([0]));
        $this->assertTrue(ArrayHelper::checkRoute(0));
    }

    public function testException1()
    {
        $this->setExpectedException('NewInventor\TypeChecker\Exception\ArgumentTypeException');
        ArrayHelper::checkRoute(false);
    }

    public function testException2()
    {
        $this->setExpectedException('NewInventor\TypeChecker\Exception\ArgumentException');
        ArrayHelper::checkRoute([false]);
    }
}
