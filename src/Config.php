<?php
/**
 * Created by PhpStorm.
 * User: inventor
 * Date: 11.09.2016
 * Time: 22:27
 */

namespace NewInventor\ConfigTool;


use NewInventor\ConfigTool\Helper\ArrayHelper;
use NewInventor\TypeChecker\Exception\ArgumentTypeException;

class Config
{
    protected static $configDir;
    protected static $config;

    const DEFAULT_KEY = 'default';
    const ALIAS_KEY = 'alias';

    /**
     * Config constructor.
     *
     * @param string $configDir
     */
    public static function init($configDir)
    {
        if(file_exists($configDir)) {
            self::$configDir = $configDir;
        }
        self::loadFiles();
    }

    protected static function loadFiles()
    {
        self::$config = [];
        $dir = new \DirectoryIterator(self::$configDir);
        foreach($dir as $fileInfo){
            if(!$fileInfo->isDot() && $fileInfo->isFile()){
                self::$config[substr($fileInfo->getFilename(), 0, -4)] = include $fileInfo->getPathname();
            }
        }
    }

    /**
     * @param string|int|array $route
     * @param mixed            $default
     *
     * @return mixed
     * @throws ArgumentTypeException
     */
    public static function get($route, $default = null)
    {
        return ArrayHelper::get(self::$config, $route, $default);
    }

    /**
     * @param string|int|array $route
     * @param mixed            $value
     *
     * @throws ArgumentTypeException
     */
    public static function set($route, $value)
    {
        ArrayHelper::set(self::$config, $route, $value);
    }

    /**
     * @param string|int|array $route
     * @param array            $data
     *
     * @throws ArgumentTypeException
     */
    public static function merge($route, array $data)
    {
        $old = self::get($route, []);
        $res = array_replace_recursive($old, $data);
        self::set($route, $res);
    }

    /**
     * @param array|string|int $baseRoute
     * @param array|string|int $route
     * @param string           $name
     * @param mixed            $default
     *
     * @return mixed|null
     */
    public static function find($baseRoute, $route, $name = '', $default = null)
    {
        $data = self::get($baseRoute);
        $el = ArrayHelper::get($data, $route);
        if (is_string($el)) {
            return $el;
        } elseif (is_array($el)) {
            if (array_key_exists($name, $el)) {
                return $el[$name];
            }
        }

        return $default;
    }

    /**
     * @param array|string|int $route
     *
     * @return bool
     */
    public static function exist($route)
    {
        return self::get($route) !== null;
    }
}