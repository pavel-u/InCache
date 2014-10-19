<?php
/**
 * This file is part of the InCache library. 
 * 
 * (c) Pavel Yukhnevich <ukhnevich@gmail.com>. 
 * 
 * For the full copyright and license information, please view the LICENSE 
 * file that was distributed with this source code.
 */

namespace spec\InCache\Code;

class GeneratorSpec extends \InCache\ObjectBehavior
{
    /**
     * @var \PhpSpec\Wrapper\Collaborator
     */
    protected $inputOutput;

    /**
     * @var \PhpSpec\Wrapper\Collaborator
     */
    protected $processorsPool;

    /**
     * @var \PhpSpec\Wrapper\Collaborator
     */
    protected $config;

    /**
     * @var \PhpSpec\Wrapper\Collaborator
     */
    protected $loader;

    /**
     * @param \PhpSpec\Wrapper\Collaborator $inputOutput
     * @param \PhpSpec\Wrapper\Collaborator $processorsPool
     * @param \PhpSpec\Wrapper\Collaborator $config
     * @param \PhpSpec\Wrapper\Collaborator $loader
     */
    public function let(
        \PhpSpec\Wrapper\Collaborator $inputOutput,
        \PhpSpec\Wrapper\Collaborator $processorsPool,
        \PhpSpec\Wrapper\Collaborator $config,
        \PhpSpec\Wrapper\Collaborator $loader
    ) {
        $inputOutput->beADoubleOf('\InCache\Code\Generator\Io');
        $processorsPool->beADoubleOf('\InCache\Code\Generator\ProcessorsPool');
        $config->beADoubleOf('\InCache\Config');
        $loader->beADoubleOf('\Composer\Autoload\ClassLoader');
        $this->beConstructedWith(
            $inputOutput,
            $processorsPool,
            $config,
            [[$loader]]
        );

        $this->inputOutput = $inputOutput;
        $this->processorsPool = $processorsPool;
        $this->config = $config;
        $this->loader = $loader;

    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('InCache\Code\Generator');
    }

    public function it_should_process_code_generation()
    {
        $this->config->getClasses()->willReturn([
            'TestClass' => ['testMethod']
        ]);

        $this->loader->findFile('TestClass')->willReturn(__FILE__);

        $this->processorsPool->setFilePath(__FILE__)->willReturn($this->processorsPool);
        $this->processorsPool->setMethods(['testMethod'])->willReturn($this->processorsPool);
        $this->config->getValue('cacheDir')->willReturn(__DIR__);

        $this->processorsPool->process()->willReturn(['' => 'data']);
        $this->inputOutput->mkdir('/var/www/cache/tests/spec/InCache/Code/.')->willReturn(true);
        $this->inputOutput->filePutContents(
            '/var/www/cache/tests/spec/InCache/Code/TestClass.php', 'data'
        )->willReturn(true);

        $this->process();
    }
}
