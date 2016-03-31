<?php
/**
 * User: Ionov George
 * Date: 17.03.2016
 * Time: 9:04
 */

namespace NewInventor\EasyForm;

use NewInventor\EasyForm\Helper\ArrayHelper;
use NewInventor\Singleton\SingletonTrait;
use NewInventor\TypeChecker\Exception\ArgumentException;
use NewInventor\TypeChecker\Exception\ArgumentTypeException;
use NewInventor\TypeChecker\TypeChecker;

class Settings
{
    use SingletonTrait;
    /** @var array */
    private static $settings;
    /** @var string */
    private $routingFilePath;

    protected function __construct()
    {
        $paths = include $this->routingFilePath;
        foreach($paths as $path){
            if(!file_exists($path)){
                continue;
            }
            $settings = include $path;
            self::$settings = array_merge_recursive(self::$settings, $settings);
        }
    }

    /**
     * @param string|int|array $route
     * @param mixed $default
     * @return mixed
     * @throws ArgumentTypeException
     */
    public function get($route, $default = null)
    {
        return ArrayHelper::get(self::$settings, $route, $default);
    }

    /**
     * @param string|int|array $route
     * @param mixed $value
     * @throws ArgumentTypeException
     */
    public function set($route, $value)
    {
        self::$settings = ArrayHelper::set(self::$settings, $route, $value);
    }

    /**
     * @param string|int|array $route
     * @param array $data
     * @throws ArgumentTypeException
     */
    public function merge($route, array $data)
    {
        $custom = $this->get($route, []);
        $res = array_replace_recursive($data, $custom);
        $this->set($route, $res);
    }

    public function find($baseRoute, $route, $className = '', $default = null)
    {
        $data = $this->get($baseRoute);
        $el = ArrayHelper::get($data, $route);
        if(is_string($el)){
            return $el;
        }elseif(is_array($el)){
            if(in_array($className, $el)){
                return $el[$className];
            }
            if($alias = ArrayHelper::get($data, 'alias') !== null){
                if(isset($el[$alias])){
                    return $el[$alias];
                }else{
                    return $el['default'];
                }
            }else{
                return $el['default'];
            }
        }

        return $default;
    }

    public function init($routingFilePath = '')
    {
        TypeChecker::getInstance()
            ->isString($routingFilePath, 'routingFilePath')
            ->throwTypeErrorIfNotValid();

        if(empty($routingFilePath)){
            $routingFilePath = './config.php';
        }
        if(file_exists($routingFilePath)){
            $this->routingFilePath = $routingFilePath;
        }else{
            throw new ArgumentException('Файл настроек не найден', 'routingFilePath');
        }
    }
}