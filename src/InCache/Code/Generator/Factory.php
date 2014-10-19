<?php
/**
 * This file is part of the InCache library. 
 * 
 * (c) Pavel Yukhnevich <ukhnevich@gmail.com>. 
 * 
 * For the full copyright and license information, please view the LICENSE 
 * file that was distributed with this source code. 

 */

namespace InCache\Code\Generator;


class Factory
{
    /**
     * @var \InCache\Config
     */
    protected $config;

    public function __construct(\InCache\Config $config = null)
    {
        $this->config = $config;
    }

    /**
     * Returns new Generator instance
     *
     * @param array $loaders
     * @return \InCache\Code\Generator
     */
    public function create($loaders)
    {
        $this->config = isset($this->config) ? $this->config : \InCache\Config::instance();
        $generator = new \InCache\Code\Generator(
            new \InCache\Code\Generator\Io(
                new \Symfony\Component\Filesystem\Filesystem, $this->config->getValue('cacheDir')
            ),
            new \InCache\Code\Generator\ProcessorsPool,
            $this->config,
            $loaders
        );
        return $generator;
    }
}
