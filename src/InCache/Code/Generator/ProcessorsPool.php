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


class ProcessorsPool
{
    /**
     * @var string
     */
    protected $filePath;

    /**
     * @var array
     */
    protected $formatted = [];

    /**
     * @var array
     */
    protected $methods = [];

    /**
     * @var array
     */
    protected $processors = [
        '\InCache\Code\Generator\Processor\Copy',
        '\InCache\Code\Generator\Processor\Proxy',
    ];

    /**
     * @var \SplObjectStorage
     */
    protected $objects;

    public function __construct()
    {
        $this->objects = new \SplObjectStorage();
        foreach ($this->processors as $processor) {
            $this->objects->attach(new $processor(new \InCache\Code\Analyzer\Factory));
        }
    }

    /**
     * @param string $filePath
     * @return $this
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;
        return $this;
    }

    /**
     * @param array $methods
     * @return $this
     */
    public function setMethods(array $methods)
    {
        $this->methods = $methods;
        return $this;
    }

    /**
     * Returns class suffix and content as array
     *
     * @return array
     */
    public function process()
    {
        foreach ($this->objects as $formatter) {
            $formatter->setMethods($this->methods);
            yield $formatter->getSuffix() => $formatter->process($this->filePath);
        }
    }
}
