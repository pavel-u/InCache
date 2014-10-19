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


class Interceptor
{
    /**
     * @var string
     */
    protected $rootPath;

    /**
     * @var string
     */
    protected $configPath;

    /**
     * @var \InCache\Code\Generator\Factory
     */
    protected $generatorFactory;

    /**
     * @var \InCache\Autoload
     */
    protected $autoload;

    /**
     * @var \InCache\Loader\Factory
     */
    protected $loaderFactory;

    /**
     * @var array
     */
    protected $loaders = [];

    public function __construct(
        \InCache\Autoload $autoload = null,
        \InCache\Loader\Factory $loaderFactory = null,
        \InCache\Code\Generator\Factory $generatorFactory = null
    ) {
        $this->autoload = isset($autoload) ? $autoload : new \InCache\Autoload();
        $this->loaderFactory = isset($loaderFactory) ? $loaderFactory : new \InCache\Loader\Factory();
        $this->generatorFactory = isset($generatorFactory) ? $generatorFactory : new \InCache\Code\Generator\Factory();
    }

    /**
     * @param string $path
     * @return $this
     */
    public function setRootPath($path)
    {
        $this->rootPath = $path;
        return $this;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function setConfigPath($path)
    {
        $this->configPath = $path;
        return $this;
    }

    /**
     * Start to intercept class loading
     */
    public function listen()
    {
        \InCache\Config::init($this->configPath);

        $loaders = $this->autoload->getFunctions();

        foreach ($loaders as &$loader) {
            $loaderToUnregister = $loader;
            if (isset($loader[0]) && ($loader[0] instanceof \Composer\Autoload\ClassLoader)) {
                $loader[0] = $this->loaderFactory->create($loader[0]);
            }
            $this->autoload->unregister($loaderToUnregister);
        }
        unset($loader);

        foreach ($loaders as $loader) {
            $this->loaders[] = $loader;
            $this->autoload->register($loader);
        }
        return $this;
    }

    /**
     * Generate classes
     *
     * @param bool $force
     * @return $this
     */
    public function generate($force = false)
    {
        $generatorFactory = $this->generatorFactory->create($this->loaders,  $this->configPath);
        $generatorFactory->process($force);
        return $this;
    }
}
