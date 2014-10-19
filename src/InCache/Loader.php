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


class Loader
{
    /**
     * @var \Composer\Autoload\ClassLoader
     */
    protected $parentLoader;

    /**
     * @var \InCache\Loader\FileLoader
     */
    protected $fileLoader;

    /**
     * @var \InCache\Config
     */
    protected $config;

    /**
     * @var array
     */
    protected $internalNamespaces = ['InCache'];

    /**
     * @param \Composer\Autoload\ClassLoader $parentLoader
     * @param \InCache\Loader\FileLoader $fileLoader
     * @param \InCache\Config $config
     */
    public function __construct(
        \Composer\Autoload\ClassLoader $parentLoader,
        \InCache\Loader\FileLoader $fileLoader,
        \InCache\Config $config
    ) {
       $this->parentLoader = $parentLoader;
       $this->fileLoader = $fileLoader;
       $this->config = $config;
    }

    /**
     * @param string $class
     * @return false|string
     */
    public function findFile($class)
    {
        return $this->parentLoader->findFile($class);
    }

    /**
     * @param string$class
     */
    public function loadClass($class)
    {
        $originalFile = $this->findFile($class);
        if ($originalFile) {
            $fileName = $this->isInternalNamespace($class)
                ? $originalFile
                : $this->cachedPath($originalFile, $class);
        } else {
            $originalFile = $this->findFile(
                str_replace(\InCache\Code\Generator\Processor\Copy::SUFFIX_VALUE, '', $class)
            );
            $fileName = $this->cachedPath($originalFile, $class);
        }
        if (empty($fileName)) {
            throw new \InCache\UnexpectedValueException("Can't find file be class '{$class}'");
        }
        $this->fileLoader->includeFile($fileName);
    }

    /**
     * @param string $class
     * @return bool
     */
    protected function isInternalNamespace($class)
    {
        $result = false;
        foreach ($this->internalNamespaces as $namespaces) {
            if (strpos($class, $namespaces) === 0) {
                $result = true;
                break;
            }
        }
        return $result;
    }

    /**
     * @param string $file
     * @param string $class
     * @return string
     */
    protected function cachedPath($file, $class)
    {
        $cacheDir = $this->config->getValue('cacheDir');
        $newFilename = $cacheDir . DIRECTORY_SEPARATOR . str_replace('\\', '/', $class). '.php';
        return file_exists($newFilename) && is_file($newFilename) ? $newFilename : $file;
    }
}