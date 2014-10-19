<?php
/**
 * This file is part of the InCache library. 
 * 
 * (c) Pavel Yukhnevich <ukhnevich@gmail.com>. 
 * 
 * For the full copyright and license information, please view the LICENSE 
 * file that was distributed with this source code.
 */

namespace spec\InCache\Code\Generator;

class FactorySpec extends \InCache\ObjectBehavior
{
    /**
     * @var \PhpSpec\Wrapper\Collaborator
     */
    protected $config;

    public function let(\PhpSpec\Wrapper\Collaborator $config)
    {
        $config->beADoubleOf('\InCache\Config');
        $this->beConstructedWith($config);
        $this->config = $config;
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('InCache\Code\Generator\Factory');
    }

    public function it_should_create_generator()
    {
        $this->config->getValue('cacheDir')->willReturn(__DIR__);
        $this->create([])->shouldBeAnInstanceOf('InCache\Code\Generator');
    }
}
