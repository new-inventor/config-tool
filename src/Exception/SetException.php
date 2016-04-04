<?php
/**
 * User: Ionov George
 * Date: 01.04.2016
 * Time: 15:40
 */

namespace NewInventor\ConfigTool\Exception;

class SetException extends \Exception
{
    /**
     * ArgumentException constructor.
     * @param string $message
     * @param string $route
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct($message = '', $route = '', $code = 0, $previous = null)
    {
        parent::__construct($this->getMessageString($message, $route), $code, $previous);
    }

    protected function getMessageString($message, $route = '')
    {
        $str = 'Ошибка при установке значения по пути';
        if(!empty($route)){
            $str .= ' "' . $route . '"';
        }
        if(!empty($message)){
            $str .= ': ' . $message;
        }

        return $str;
    }
}