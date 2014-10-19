<?php
/**
 * This file is part of the InCache library. 
 * 
 * (c) Pavel Yukhnevich <ukhnevich@gmail.com>. 
 * 
 * For the full copyright and license information, please view the LICENSE 
 * file that was distributed with this source code. 

 */

namespace spec\InCache\Loader;

class FactorySpec extends \InCache\ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('InCache\Loader\Factory');
    }

    /**
     * @param \Prophecy\Prophecy\ObjectProphecy $parentLoader
     * @param \Prophecy\Prophecy\ObjectProphecy $config
     */
    public function it_should_create_loader(
        \Prophecy\Prophecy\ObjectProphecy $parentLoader,
        \Prophecy\Prophecy\ObjectProphecy $config
    ) {
        $parentLoader->beADoubleOf('\Composer\Autoload\ClassLoader');
        $config->beADoubleOf('\InCache\Config');
        $this->create($parentLoader, $config);
    }
}
