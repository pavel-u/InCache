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

class Config extends \InCache\SharedObject
{
    /**
     * @var \SimpleXMLIterator
     */
    protected $config;

    /**
     * @var array
     */
    protected $classes = [];

    /**
     * @var array
     */
    protected $evict = [];

    /**
     * @var array
     */
    protected $configValues = [];

    /**
     * @var array
     */
    protected $types = [];

    /**
     * @param string $configPath
     * @throws \InCache\UnexpectedValueException
     */
    public function __construct($configPath)
    {
        $xml = new \DOMDocument();
        $xml->load($configPath);
        if (!$xml->schemaValidate(__DIR__ . DIRECTORY_SEPARATOR . 'cache.xsd')) {
            throw new \InCache\UnexpectedValueException(
                'Please, check your config file'
            );
        }

        $this->config = new \SimpleXMLIterator(file_get_contents($configPath));

        $this->collect();
    }

    /**
     * @return array
     */
    public function getClasses()
    {
        return $this->classes;
    }

    /**
     * @return $this
     */
    protected function collect()
    {
        foreach($this->config as $item) {
            $methodName = 'collect' . ucfirst($item->getName());
            if (method_exists($this, $methodName)) {
                $this->{$methodName}($item);
            }
        }
        return $this;
    }

    /**
     * @param \SimpleXMLIterator $item
     */
    protected function collectCaching(\SimpleXMLIterator $item)
    {
        $this->classes[(string)$item['class']][(string)$item['method']] = [
            'class' => (string)$item['class'],
            'method' => (string)$item['method'],
            'ttl' => (int)$item['ttl'],
            'type' => (string)$item['type'],
            'key' => (string)$item['key'],
            'action' => 'cache',
        ];
    }

    /**
     * @param \SimpleXMLIterator $item
     */
    protected function collectEvict($item)
    {
        $this->classes[(string)$item['class']][(string)$item['method']] = [
            'class' => (string)$item['class'],
            'method' => (string)$item['method'],
            'key' => (string)$item['key'],
            'type' => (string)$item['type'],
            'action' => 'evict',
        ];
    }

    /**
     * @param \SimpleXMLIterator $item
     */
    protected function collectConfig($item)
    {
        foreach($item as $argument) {
            $value = (string)$argument['value'];
            $this->configValues[(string)$argument['name']] = $value;
        }
    }

    /**
     * @param \SimpleXMLIterator $item
     */
    protected function collectTypes($item)
    {
        foreach ($item as $argument) {
            $options = [];
            foreach ($argument as $option) {
                $options[(string)$option['name']] = (string)$option['value'];
            }
            $this->types[(string)$argument['name']] = [
                'driver' => (string)$argument['driver'],
                'pool' => (string)$argument['pool'],
                'options' => $options,
            ];
        }
    }

    /**
     * @param string $key
     * @return string
     */
    public function getValue($key)
    {
        return $this->configValues[$key];
    }

    /**
     * @return array
     */
    public function getTypes()
    {
        return $this->types;
    }
}
