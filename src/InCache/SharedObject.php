<?php
/**
 * This file is part of the InCache library. 
 * 
 * (c) Pavel Yukhnevich <ukhnevich@gmail.com>. 
 * 
 * For the full copyright and license information, please view the LICENSE 
 * file that was distributed with this source code. 

 */

namespace InCache;


abstract class SharedObject
{
    /**
     * @var \InCache\SharedObject[]
     */
    protected static $instances = [];

    /**
     * @param $parameter
     */
    public static function init($parameter)
    {
        if (!isset(self::$instances[get_called_class()])) {
            self::$instances[get_called_class()] = new static($parameter);
        }
    }

    /**
     * @return \InCache\SharedObject
     */
    public static function instance()
    {
        if (!isset(self::$instances[get_called_class()])) {
            self::$instances[get_called_class()] = new static();
        }
        return self::$instances[get_called_class()];
    }
}
