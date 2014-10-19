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


class PoolFactory extends \InCache\SharedObject
{
    /**
     * @var array
     */
    protected $types = [];

    /**
     * @param Config $config
     */
    public function __construct(\InCache\Config $config = null)
    {
        /** @var \InCache\Config $config */
        $config = isset($config) ? $config : \InCache\Config::instance();
        $this->types = $config->getTypes();
    }

    /**
     * @param string $type
     * @return \Stash\Interfaces\PoolInterfacemixed
     * @throws UnexpectedValueException
     */
    public function get($type)
    {
        if (!isset($this->pools[$type])) {
            $driverClass = $this->types[$type]['driver'];
            $poolClass = $this->types[$type]['pool'];
            $driver = new $driverClass();
            if (!is_a($driver, '\Stash\Interfaces\DriverInterface')) {
                throw new \InCache\UnexpectedValueException(
                    'Driver should implements \Stash\Interfaces\DriverInterface interface'
                );
            }
            $driver->setOptions($this->types[$type]['options']);
            $this->pools[$type] = new $poolClass($driver);

            if (!is_a($this->pools[$type], '\Stash\Interfaces\PoolInterface')) {
                throw new \InCache\UnexpectedValueException(
                    'Pool should implements \Stash\Interfaces\PoolInterface interface'
                );
            }
        }
        return $this->pools[$type];
    }
} 