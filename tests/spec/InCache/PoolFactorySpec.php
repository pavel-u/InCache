<?php
/**
 * This file is part of the InCache library. 
 * 
 * (c) Pavel Yukhnevich <ukhnevich@gmail.com>. 
 * 
 * For the full copyright and license information, please view the LICENSE 
 * file that was distributed with this source code. 

 */

namespace spec\InCache;

class PoolFactorySpec extends \InCache\ObjectBehavior
{
    /**
     * @var \PhpSpec\Wrapper\Collaborator
     */
    protected $config;

    /**
     * @param \PhpSpec\Wrapper\Collaborator $config
     */
    public function let(\PhpSpec\Wrapper\Collaborator $config)
    {
        $config->beADoubleOf('\InCache\Config');
        $config->getTypes()->willReturn([
            'valid' => [
                'driver' => '\Stash\Driver\FileSystem',
                'pool' => '\Stash\Pool',
                'options' => [],
            ],
            'invalidPool' => [
                'driver' => '\Stash\Driver\FileSystem',
                'pool' => 'StdClass',
                'options' => [],
            ],
            'invalidDriver' => [
                'driver' => 'StdClass',
                'pool' => '\Stash\Pool',
                'options' => [],
            ],
        ]);
        $this->beConstructedWith($config);

        $this->config = $config;
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('InCache\PoolFactory');
    }

    public function it_should_create_pool()
    {
        $this->get('valid')->shouldBeAnInstanceOf('\Stash\Pool');
    }

    public function it_should_check_interfaces()
    {
        $this->shouldThrow('\InCache\UnexpectedValueException')->duringGet('invalidPool');
        $this->shouldThrow('\InCache\UnexpectedValueException')->duringGet('invalidDriver');
    }
}
