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

class InterceptorSpec extends \InCache\ObjectBehavior
{
    /**
     * @var \PhpSpec\Wrapper\Collaborator
     */
    protected $generatorFactory;

    /**
     * @var \PhpSpec\Wrapper\Collaborator
     */
    protected $autoload;

    /**
     * @var \PhpSpec\Wrapper\Collaborator
     */
    protected $loaderFactory;

    /**
     * @param \PhpSpec\Wrapper\Collaborator $autoload
     * @param \PhpSpec\Wrapper\Collaborator $loaderFactory
     * @param \PhpSpec\Wrapper\Collaborator $generatorFactory
     */
    public function let(
        \PhpSpec\Wrapper\Collaborator $autoload,
        \PhpSpec\Wrapper\Collaborator $loaderFactory,
        \PhpSpec\Wrapper\Collaborator $generatorFactory
    ) {
        $autoload->beADoubleOf('\InCache\Autoload');
        $loaderFactory->beADoubleOf('\InCache\Loader\Factory');
        $generatorFactory->beADoubleOf('\InCache\Code\Generator\Factory');
        $this->beConstructedWith($autoload, $loaderFactory, $generatorFactory);

        $this->autoload = $autoload;
        $this->loaderFactory = $loaderFactory;
        $this->generatorFactory = $generatorFactory;
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('InCache\Interceptor');
    }

    public function it_should_be_configurable()
    {
        $this->setRootPath(__DIR__)->shouldReturn($this);
        $this->setConfigPath($this->getConfigPath())->shouldReturn($this);
    }

    /**
     * @param \Prophecy\Prophecy\ObjectProphecy $loader
     */
    public function it_should_start_listen(\Prophecy\Prophecy\ObjectProphecy $loader)
    {
        $this->it_should_be_configurable();
        $loader->beADoubleOf('\Composer\Autoload\ClassLoader');
        $this->autoload->getFunctions()->willReturn([[$loader]]);
        $this->loaderFactory->create($loader)->willReturn($loader);
        $this->autoload->unregister([$loader])->willReturn(true);
        $this->autoload->register([$loader])->willReturn(true);
        $this->listen();
    }

    /**
     * @param \Prophecy\Prophecy\ObjectProphecy $generator
     */
    public function it_should_generate(\Prophecy\Prophecy\ObjectProphecy $generator)
    {
        $this->it_should_be_configurable();

        $generator->beADoubleOf('\Incache\Code\Generator');
        $generator->process(true)->willReturn(null);
        $generator->process(false)->willReturn(null);
        $this->generatorFactory->create([],  $this->getConfigPath())->willReturn($generator);
        $this->generate(true);
        $this->generate(false);
    }

    protected function getConfigPath()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'ConfigSpec' . DIRECTORY_SEPARATOR . 'cache.xml.fixture';
    }
}
