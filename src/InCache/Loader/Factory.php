<?php
/**
 * This file is part of the InCache library. 
 * 
 * (c) Pavel Yukhnevich <ukhnevich@gmail.com>. 
 * 
 * For the full copyright and license information, please view the LICENSE 
 * file that was distributed with this source code. 

 */

namespace InCache\Loader;


class Factory
{
    /**
     * @param \Composer\Autoload\ClassLoader $parentLoader
     * @param \InCache\Config $config
     * @return \InCache\Loader
     */
    public function create(
        \Composer\Autoload\ClassLoader $parentLoader,
        \InCache\Config $config = null
    ) {
        return new \InCache\Loader(
            $parentLoader,
            new \InCache\Loader\FileLoader,
            isset($config) ? $config : \InCache\Config::instance()
        );
    }
}
