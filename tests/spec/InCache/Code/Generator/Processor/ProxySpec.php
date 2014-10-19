<?php
/**
 * This file is part of the InCache library. 
 * 
 * (c) Pavel Yukhnevich <ukhnevich@gmail.com>. 
 * 
 * For the full copyright and license information, please view the LICENSE 
 * file that was distributed with this source code.
 */

namespace spec\InCache\Code\Generator\Processor;

class ProxySpec extends \InCache\ObjectBehavior
{
    /**
     * @var \PhpSpec\Wrapper\Collaborator
     */
    protected $analyzerFactory;

    /**
     * @param \PhpSpec\Wrapper\Collaborator $analyzerFactory
     */
    public function let(\PhpSpec\Wrapper\Collaborator $analyzerFactory)
    {
        $analyzerFactory->beADoubleOf('\InCache\Code\Analyzer\Factory');
        $this->beConstructedWith($analyzerFactory);
        $this->analyzerFactory = $analyzerFactory;
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('InCache\Code\Generator\Processor\Proxy');
    }

    public function i_should_set_methods()
    {
        $this->setMethods('lol')->willReturn($this);
    }

    public function it_should_return_empty_suffix()
    {
        $this->getSuffix()->shouldReturn('');
    }

    public function it_should_process_generation()
    {
        $prophet = new \Prophecy\Prophet();
        $analyzer = $prophet->prophesize('InCache\Code\Analyzer');
        $analyzer->setFile($this->getFixturePath('source'))->willReturn($analyzer);
        $analyzer->scan()->willReturn($analyzer);
        $analyzer->getNamespace()->willReturn('Vendor');
        $analyzer->getClassName()->willReturn('TestClass');
        $analyzer->getMethodVisibility('testEvictMethod')->willReturn('public');
        $analyzer->getMethodSignature('testEvictMethod')->willReturn('testEvictMethod($a = 3)');

        $analyzer->getMethodVisibility('testMethod')->willReturn('public');
        $analyzer->getMethodSignature('testMethod')->willReturn('testMethod($a = 1)');

        $analyzer->getMethodVisibility('testMethod2')->willReturn('public');
        $analyzer->getMethodSignature('testMethod2')->willReturn('testMethod2($a = 2)');

        $this->analyzerFactory->create()->willReturn(
            $analyzer
        );

        $methods = $this->includeFixture('methods');
        $this->setMethods($methods);
        $this->process($this->getFixturePath('source'))->shouldReturn($this->getFixture('expected'));
    }
}
