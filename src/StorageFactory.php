<?php
/**
 * Created by PhpStorm.
 * User: inventor
 * Date: 14.10.2016
 * Time: 15:28
 */

namespace NewInventor\ConfigTool;


use NewInventor\TypeChecker\TypeCheck;

class StorageFactory
{
    use TypeCheck;
    protected static $availableStorageTypes = ['array'];

    /**
     * @param string $type
     *
     * @return StorageInterface
     */
    public static function make($type = 'array')
    {
        $available = self::$availableStorageTypes;
        if(self::param()->callback(function ($value) use ($available) {
            return in_array($value, $available, 'true');
        })->result()){
            $className = __NAMESPACE__ . '\Storages\\' . ucfirst($type) . 'Storage';
            return new $className();
        }
        return new $type();
    }
}