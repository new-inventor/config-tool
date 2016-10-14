<?php
/**
 * Created by PhpStorm.
 * User: inventor
 * Date: 11.09.2016
 * Time: 22:27
 */

namespace NewInventor\ConfigTool;

use NewInventor\TypeChecker\Exception\ArgumentTypeException;
use NewInventor\TypeChecker\TypeCheck;

class Config
{
    use TypeCheck;
    /** @var StorageInterface */
    public static $config;

    /**
     * Config constructor.
     *
     * @param string $configDir
     *
     * @throws ArgumentTypeException
     */
    public static function init($configDir)
    {
        self::param()->callback(function ($value) {
            return file_exists($value);
        })->fail();
        self::loadFiles($configDir);
    }

    protected static function loadFiles($configDir)
    {
        $config = [];
        $dir = new \DirectoryIterator($configDir);
        foreach($dir as $fileInfo){
            if(!$fileInfo->isDot() && $fileInfo->isFile()){
                $config[substr($fileInfo->getFilename(), 0, -4)] = include $fileInfo->getPathname();
            }
        }

        self::$config = StorageFactory::make('array')->init($config);
    }

    /**
     * @param string|null|array $route
     * @param mixed            $default
     *
     * @return mixed
     * @throws ArgumentTypeException
     */
    public static function get($route, $default = null)
    {
        return self::$config->get($route, $default);
    }

    /**
     * @param string|int|array $route
     * @param mixed            $value
     *
     * @throws ArgumentTypeException
     */
    public static function set($route, $value)
    {
        self::$config->set($route, $value);
    }

    /**
     * @param string|int|array $route
     * @param array            $data
     *
     * @throws ArgumentTypeException
     */
    public static function merge($route, array $data)
    {
        $old = self::$config->get($route, []);
        $res = array_replace_recursive($old, $data);
        self::$config->set($route, $res);
    }

    /**
     * @param array|string|int $route
     *
     * @return bool
     */
    public static function has($route)
    {
        return self::$config->has($route);
    }

    /**
     * @param array|string|int $route
     */
    public static function delete($route)
    {
        self::$config->delete($route);
    }
}