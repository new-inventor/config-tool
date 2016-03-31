<?php
/**
 * User: Ionov George
 * Date: 15.02.2016
 * Time: 17:40
 */

namespace NewInventor\EasyForm\Helper;

use NewInventor\TypeChecker\Exception\ArgumentException;
use NewInventor\TypeChecker\Exception\ArgumentTypeException;
use NewInventor\TypeChecker\SimpleTypes;
use NewInventor\TypeChecker\TypeChecker;

class ArrayHelper
{
    public static function get(array $elements, $route, $default = null)
    {
        if (is_array($route)) {
            return self::getByRoute($elements, $route, $default);
        }

        return self::getByIndex($elements, $route, $default);
    }

    /**
     * @param array $elements
     * @param string[]|int[] $route
     * @param mixed|null $default
     * @return mixed
     * @throws ArgumentException
     */
    public static function getByRoute(array $elements, array $route = [], $default = null)
    {
        self::checkRoute($route);

        foreach ($route as $levelName) {
            if (!isset($elements[$levelName])) {
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
        self::checkRoute($route);

        if (isset($elements[$route])) {
            return $elements[$route];
        }

        return $default;
    }


    public static function set(array $elements, $route, $value)
    {
        self::checkRoute($route);

        if (is_array($route)) {
            $resArrayRoute = '';
            foreach ($route as $levelName) {
                $resArrayRoute .= '[' . (is_int($levelName) ? $levelName : "'$levelName'") . ']';
            }
            eval('$elements' . $resArrayRoute . ' = $value;');
        } else {
            $elements[$route] = $value;
        }

        return $elements;
    }

    public static function checkRoute($route)
    {
        if (is_array($route)){
            TypeChecker::getInstance()
                ->checkArray($route, [SimpleTypes::STRING, SimpleTypes::INT], 'route')
                ->throwCustomErrorIfNotValid('Елементы должны быть или целыми числами или строками.');
        }else{
            TypeChecker::getInstance()
                ->check($route, [SimpleTypes::STRING, SimpleTypes::INT], 'route')
                ->throwTypeErrorIfNotValid();
        }
    }
}