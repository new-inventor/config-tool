<?php
/**
 * Created by PhpStorm.
 * User: inventor
 * Date: 14.10.2016
 * Time: 15:19
 */

namespace NewInventor\ConfigTool;


interface StorageInterface
{
    /**
     * @param array $config
     *
     * @return static
     */
    public function init(array $config);

    /**
     * @param string|null|array $route
     * @param mixed             $default
     *
     * @return mixed
     */
    public function get($route, $default);

    /**
     * @param string|null|array $route
     * @param mixed             $value
     *
     * @return static
     */
    public function set($route, $value);

    /**
     * @param string|null|array $route
     *
     * @return static
     */
    public function delete($route);

    /**
     * @param string|null|array $route
     *
     * @return bool
     */
    public function has($route);

    /**
     * @param string|null|array $route
     * @param mixed             $value
     *
     * @return static
     */
    public function push($route, $value);

    /**
     * @param string|null|array $route
     * @param mixed             $value
     *
     * @return static
     */
    public function prepend($route, $value);
}