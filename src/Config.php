<?php
/**
 * User: Ionov George
 * Date: 17.03.2016
 * Time: 9:04
 */

namespace NewInventor\ConfigTool;

use NewInventor\ConfigTool\Helper\ArrayHelper;
use NewInventor\Patterns\SingletonTrait;
use NewInventor\TypeChecker\Exception\ArgumentException;
use NewInventor\TypeChecker\Exception\ArgumentTypeException;
use NewInventor\TypeChecker\SimpleTypes;
use NewInventor\TypeChecker\TypeChecker;

class Config
{
    use SingletonTrait;
    /** @var array */
    protected static $settings = [];
    /** @var array */
    protected $routingPaths;

    const DEFAULT_KEY = 'default';
    const ALIAS_KEY = 'alias';

    /**
     * Config constructor.
     * @param array $routing
     */
    protected function __construct(array $routing = [])
    {
        $this->setRoutingPaths($routing);

        foreach($this->routingPaths as $path){
            if(!file_exists($path)){
                continue;
            }
            $settings = include $path;
            $this->_merge([], $settings);
        }
    }

    /**
     * @param array $routing
     * @throws ArgumentException
     * @throws \Exception
     */
    protected function setRoutingPaths(array $routing)
    {
        TypeChecker::getInstance()
            ->checkArray($routing, [SimpleTypes::STRING], 'routing')
            ->throwCustomErrorIfNotValid('Список путей к файлам настроек должен быть массивом строк.');
        $this->routingPaths = $routing;
    }

    /**
     * @param string|int|array $route
     * @param mixed $default
     * @return mixed
     * @throws ArgumentTypeException
     */
    public static function get($route, $default = null)
    {
        return static::getInstance()->_get($route, $default);
    }

    /**
     * @param string|int|array $route
     * @param null $default
     * @return mixed|null
     */
    public function _get($route, $default = null)
    {
        return ArrayHelper::get(static::$settings, $route, $default);
    }

    /**
     * @param string|int|array $route
     * @param mixed $value
     * @throws ArgumentTypeException
     */
    public static function set($route, $value)
    {
        static::getInstance()->_set($route, $value);
    }

    /**
     * @param string|int|array $route
     * @param mixed $value
     * @throws Exception\SetException
     */
    public function _set($route, $value)
    {
        ArrayHelper::set(static::$settings, $route, $value);
    }

    /**
     * @param string|int|array $route
     * @param array $data
     * @throws ArgumentTypeException
     */
    public static function merge($route, array $data)
    {
        static::getInstance()->_merge($route, $data);
    }

    /**
     * @param string|int|array $route
     * @param array $data
     * @throws ArgumentTypeException
     */
    public function _merge($route, array $data)
    {
        $custom = $this->_get($route, []);
        $res = array_replace_recursive($data, $custom);
        $this->_set($route, $res);
    }

    /**
     * @param string|int|array $route
     * @param string $filePath
     * @throws ArgumentException
     */
    public static function mergeFile($route, $filePath)
    {
        static::getInstance()->_mergeFile($route, $filePath);
    }

    /**
     * @param string|int|array $route
     * @param string $filePath
     * @throws ArgumentException
     */
    public function _mergeFile($route, $filePath)
    {
        TypeChecker::getInstance()
            ->isString($filePath, 'fileName')
            ->throwTypeErrorIfNotValid();

        if(file_exists($filePath)){
            $config = include $filePath;
            $this->_merge($route, $config);
        }else{
            throw new ArgumentException('Файл роутинга настроек не найден', 'routingFilePath');
        }
    }

    /**
     * @param array|string|int $baseRoute
     * @param array|string|int $route
     * @param string $name
     * @param mixed $default
     * @return mixed|null
     */
    public static function find($baseRoute, $route, $name = '', $default = null)
    {
        return static::getInstance()->_find($baseRoute, $route, $name, $default);
    }

    /**
     * @param array|string|int $baseRoute
     * @param array|string|int $route
     * @param string $name
     * @param mixed $default
     * @return mixed|null
     */
    public function _find($baseRoute, $route, $name = '', $default = null)
    {
        $data = $this->_get($baseRoute);
        $el = ArrayHelper::get($data, $route);
        if(is_string($el)){
            return $el;
        }elseif(is_array($el)){
            if(array_key_exists($name, $el)){
                return $el[$name];
            }
            $alias = $this->getAlias($name, $baseRoute);
            if($alias !== null && array_key_exists($alias, $el)){
                return $el[$alias];
            }
            if(isset($el[static::DEFAULT_KEY])){
                return $el[static::DEFAULT_KEY];
            }
        }

        return $default;
    }

    /**
     * @param array|string|int $route
     * @return bool
     */
    public function exist($route)
    {
        return $this->get($route) != null;
    }

    public function getDefault($route)
    {
        return static::get(array_push($route, static::DEFAULT_KEY));
    }

    public function getAlias($name, $baseRoute = [])
    {
        return static::get(array_merge($baseRoute, [static::ALIAS_KEY, $name]));
    }

    public function aliasesExists($baseRoute = [])
    {
        return $this->exist(array_push($baseRoute, static::ALIAS_KEY));
    }
}