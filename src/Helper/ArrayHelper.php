<?php
/**
 * User: Ionov George
 * Date: 15.02.2016
 * Time: 17:40
 */

namespace NewInventor\ConfigTool\Helper;

use NewInventor\ConfigTool\Exception\SetException;
use NewInventor\TypeChecker\Exception\ArgumentException;
use NewInventor\TypeChecker\Exception\ArgumentTypeException;
use NewInventor\TypeChecker\SimpleTypes;
use NewInventor\TypeChecker\TypeChecker;

class ArrayHelper
{
    /**
     * @param array $elements
     * @param array|string|int $route
     * @param mixed $default
     * @return mixed|null
     */
    public static function get(array $elements, $route, $default = null)
    {
        if (is_array($route)) {
            return self::getByRoute($elements, $route, $default);
        }

        return self::getByIndex($elements, $route, $default);
    }

    /**
     * @param array $elements
     * @param array $route
     * @param mixed|null $default
     * @return mixed
     * @throws ArgumentException
     */
    public static function getByRoute(array $elements, array $route = [], $default = null)
    {
        self::checkArrayRoute($route);

        foreach ($route as $levelName) {
            if (!is_array($elements) || !array_key_exists($levelName, $elements)) {
                return $default;
            }
            $elements = $elements[$levelName];
        }

        return $elements;
    }

    /**
     * @param array $elements
     * @param string|int $route
     * @param null $default
     * @return null
     * @throws ArgumentTypeException
     */
    public static function getByIndex(array $elements, $route, $default = null)
    {
        self::checkKeyRoute($route);

        if (array_key_exists($route, $elements)) {
            return $elements[$route];
        }

        return $default;
    }


    /**
     * Set value in settings by route
     *
     * @param array $elements
     * @param array|string|int $route
     * @param mixed $value
     * @return array
     *
     * @throws SetException
     */
    public static function set(array &$elements, $route, $value)
    {
        self::checkRoute($route);

        if (is_array($route)) {
            $resArrayRoute = '';
            foreach ($route as $levelName) {
                $resArrayRoute .= '[' . (is_int($levelName) ? $levelName : "'$levelName'") . ']';
            }
            try{
                eval('$elements' . $resArrayRoute . ' = $value;');
            }catch(\Exception $e){
                throw new SetException('Нельзя преобразовывать значения в массив.', $resArrayRoute);
            }
        } else {
            $elements[$route] = $value;
        }
    }

    /**
     * @param array|string|int $route
     * @throws ArgumentTypeException
     * @throws \Exception
     *
     * @return bool
     */
    public static function checkRoute($route)
    {
        if (is_array($route)){
            self::checkArrayRoute($route);
        }else{
            self::checkKeyRoute($route);
        }
        return true;
    }

    protected static function checkArrayRoute($route)
    {
        TypeChecker::getInstance()
            ->checkArray($route, [SimpleTypes::STRING, SimpleTypes::INT], 'route')
            ->throwCustomErrorIfNotValid('Елементы должны быть или целыми числами или строками.');
    }

    protected static function checkKeyRoute($route)
    {
        TypeChecker::getInstance()
            ->check($route, [SimpleTypes::STRING, SimpleTypes::INT], 'route')
            ->throwTypeErrorIfNotValid();
    }
}