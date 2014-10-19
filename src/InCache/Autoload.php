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


class Autoload
{
    /**
     * @return array
     */
    public function getFunctions()
    {
        return spl_autoload_functions();
    }

    /**
     * @param callback $loader
     * @return bool
     */
    public function register($loader)
    {
        return spl_autoload_register($loader);
    }

    /**
     * @param mixed $loader
     * @return bool
     */
    public function unregister($loader)
    {
        return spl_autoload_unregister($loader);
    }
}
