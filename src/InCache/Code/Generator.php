<?php
/**
 * This file is part of the InCache library. 
 * 
 * (c) Pavel Yukhnevich <ukhnevich@gmail.com>. 
 * 
 * For the full copyright and license information, please view the LICENSE 
 * file that was distributed with this source code. 

 */

namespace InCache\Code;


class Generator
{
    /**
     * @var Generator\Io
     */
    protected $inputOutput;

    /**
     * @var Generator\ProcessorsPool
     */
    protected $processorsPool;

    /**
     * @var \InCache\Config
     */
    protected $config;

    /**
     * @var array
     */
    protected $loaders;

    /**
     * @param \InCache\Code\Generator\Io $inputOutput
     * @param \InCache\Code\Generator\ProcessorsPool $processorsPool
     * @param \InCache\Config $config
     * @param $loaders
     */
    public function __construct(
        \InCache\Code\Generator\Io $inputOutput,
        \InCache\Code\Generator\ProcessorsPool $processorsPool,
        \InCache\Config $config,
        $loaders
    ) {
        $this->inputOutput = $inputOutput;
        $this->processorsPool = $processorsPool;
        $this->config = $config;
        $this->loaders = $loaders;
    }

    /**
     * Process interceptors generation
     *
     * @param bool $force
     * @throws \InCache\UnexpectedValueException
     */
    public function process($force = false)
    {
        foreach ($this->config->getClasses() as $class => $methods) {
            $file = $this->findFileByClass($class);
            $pool = $this->processorsPool
                ->setFilePath($file)
                ->setMethods($methods);

            if (!$force && file_exists(
                    $this->config->getValue('cacheDir')
                    . DIRECTORY_SEPARATOR . $this->classToPath($class))) {
                continue;
            }
            foreach ($pool->process() as $suffix => $data) {
                $this->inputOutput->mkdir($this->config->getValue('cacheDir')
                    . DIRECTORY_SEPARATOR . dirname($this->classToPath($class, $suffix)));
                $this->inputOutput->filePutContents(
                    $this->config->getValue('cacheDir')
                    . DIRECTORY_SEPARATOR . $this->classToPath($class, $suffix), $data
                );
            }
        }
    }

    /**
     * @param string $class
     * @param string $suffix
     * @return string
     */
    protected function classToPath($class, $suffix = '')
    {
        return str_replace('\\', '/', $class) . "{$suffix}.php";
    }

    /**
     * @param $class
     * @return null|string
     * @throws \InCache\UnexpectedValueException
     */
    protected function findFileByClass($class)
    {
        $file = null;
        foreach ($this->loaders as $loader) {
            $file = $loader[0]->findFile($class);
            if (file_exists($file)) {
                break;
            }
        }
        if ($file === null) {
            throw new \InCache\UnexpectedValueException("Can't find file by class '{$class}'");
        }
        return $file;
    }
}
