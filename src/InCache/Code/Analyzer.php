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


class Analyzer
{
    /**
     * @var string
     */
    protected $file;

    /**
     * @var \PHP_Reflect
     */
    protected $reflect;

    /**
     * @va rarray
     */
    protected $data;

    /**
     * @param \PHP_Reflect $reflect
     */
    public function __construct(\PHP_Reflect $reflect)
    {
        $this->reflect = $reflect;
    }

    /**
     * @param string $file
     * @return $this
     */
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * @return $this
     * @throws \InCache\RuntimeException
     */
    public function scan()
    {
        try {
            $this->reflect->scan($this->file);
            $this->data = $this->reflect->getClasses();
        } catch (\Exception $e) {
            throw new \InCache\RuntimeException($e->getMessage());
        }
        return $this;
    }

    /**
     * @return mixed|null
     */
    public function getNamespace()
    {
        reset($this->data);
        $result = key($this->data);
        return $result !== '\\' ? $result : null;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return key($this->data[$this->getNamespace()]);
    }

    /**
     * @param $method
     * @return string
     */
    public function getMethodSignature($method)
    {
        $method = $this->getMethod($method);
        return $method['signature'];
    }

    /**
     * @param string $method
     * @return string
     */
    public function getMethodVisibility($method)
    {
        $method = $this->getMethod($method);
        return $method['visibility'];
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return $this->data[$this->getNamespace()][$this->getClassName()]['parent'];
    }

    /**
     * @return string
     */
    public function getInterfaces()
    {
        return implode(', ', $this->data[$this->getNamespace()][$this->getClassName()]['interfaces']);
    }

    /**
     * @param string $method
     * @return array
     * @throws \InCache\UnexpectedValueException
     */
    protected function getMethod($method)
    {
        if (!isset($this->data[$this->getNamespace()][$this->getClassName()]['methods'][$method])) {
            throw new \InCache\UnexpectedValueException("Specified method '{$method}' is not found");
        }
        return $this->data[$this->getNamespace()][$this->getClassName()]['methods'][$method];
    }
}
