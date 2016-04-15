<?php

/**
 * User: Ionov George
 * Date: 01.04.2016
 * Time: 15:12
 */
use \NewInventor\ConfigTool\Config;

class ConfigTest extends PHPUnit_Framework_TestCase
{
    public function SetUp()
    {
        Config::getInstance([
            __DIR__ . '/config/test.php',
        ]);
    }

    public function testInit()
    {
        $this->assertEquals('qwe', Config::get(['test', 'test']));
        $this->assertEquals('qwe', Config::get(['test', 'test', 0], 'qwe'));
    }

    public function testGet()
    {
        $this->assertNull(Config::get(0));
        $this->assertNull(Config::get('2'));
        $this->assertNull(Config::get('zxc'));
        $this->assertEquals('asd', Config::get('123'));
        $this->assertEquals('123', Config::get(['test', 'default']));
        $this->assertEquals('123', Config::get(['test', '11'], '123'));
        $this->assertEquals('qwe', Config::get(['11', 'test'], 'qwe'));
    }

    public function testSet()
    {
        Config::set('qwe', ['asd' => '123', 'asd']);
        $this->assertEquals('123', Config::get(['qwe', 'asd']));
        Config::set(['test', 'poi'], false);
        $this->assertFalse(Config::get(['test', 'poi']));
    }

    public function testFind()
    {
        $this->assertEquals('123', Config::find([], 'test', 'asd'));
        $this->assertEquals('123', Config::find([], 'test', 'asd', 321));
        $this->assertEquals(321, Config::find([], 'test1', 'asd', 321));
        $this->assertEquals('eqwew', Config::find([], 'test', 'name name name'));
    }

    public function testMerge()
    {
        Config::merge('status', ['array' => ['dfs', 'dfsdf'], 'asdasd']);
        $this->assertEquals('asdasd', Config::get(['status', 0]));
    }

    public function testFile()
    {
        Config::mergeFile('status', __DIR__ . '/config/main.php');
        $this->assertEquals('asdasd', Config::get(['status', 0]));
    }
}
