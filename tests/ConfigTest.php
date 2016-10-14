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
        self::assertNotEquals('qwe', Config::get(['test', 'test']));
        self::assertEquals('qwe', Config::get(['test', 'test', 0], 'qwe'));
    }

    public function testGet()
    {
        self::assertNotNull(Config::get(null));
        self::assertNull(Config::get(0));
        self::assertNull(Config::get('2'));
        self::assertNull(Config::get('zxc'));
        self::assertArrayHasKey('123', Config::get('test'));
        self::assertEquals('123', Config::get(['test', 'test', 'default']));
        self::assertEquals('123', Config::get(['test', 'test', '11'], '123'));
        self::assertEquals('qwe', Config::get(['test', '11', 'test'], 'qwe'));
    }

    public function testSet()
    {
        Config::set('qwe', ['asd' => '123', 'asd']);
        self::assertEquals('123', Config::get(['qwe', 'asd']));
        Config::set(['test', 'poi'], false);
        self::assertFalse(Config::get(['test', 'poi']));
    }

    public function testMerge()
    {
        Config::merge('status', ['array' => ['dfs', 'dfsdf'], 'asdasd']);
        self::assertEquals('asdasd', Config::get(['status', 0]));
    }
}
