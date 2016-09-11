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
        Config::init(__DIR__ . '/config');
    }

    public function testInit()
    {
        $this->assertNotEquals('qwe', Config::get(['test', 'test']));
        $this->assertEquals('qwe', Config::get(['test', 'test', 0], 'qwe'));
    }

    public function testGet()
    {
        $this->assertNull(Config::get(0));
        $this->assertNull(Config::get('2'));
        $this->assertNull(Config::get('zxc'));
        $this->assertArrayHasKey('123', Config::get('test'));
        $this->assertEquals('123', Config::get(['test', 'test', 'default']));
        $this->assertEquals('123', Config::get(['test', 'test', '11'], '123'));
        $this->assertEquals('qwe', Config::get(['test', '11', 'test'], 'qwe'));
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
        $this->assertNull(Config::find([], 'test', 'asd'));
        $this->assertArrayHasKey('123', Config::find([], 'test', 'test1'));
        $this->assertArrayHasKey('123', Config::find([], 'test', 'test1', 321));
        $this->assertEquals(321, Config::find([], 'test1', 'asd', 321));
    }

    public function testMerge()
    {
        Config::merge('status', ['array' => ['dfs', 'dfsdf'], 'asdasd']);
        $this->assertEquals('asdasd', Config::get(['status', 0]));
    }
}
