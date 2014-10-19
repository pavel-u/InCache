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

class AnalyzerSpec extends \InCache\ObjectBehavior
{
    /**
     * @var \PhpSpec\Wrapper\Collaborator
     */
    protected $reflect;

    public function let(\PhpSpec\Wrapper\Collaborator $reflect)
    {
        $reflect->beADoubleOf('PHP_Reflect');
        $this->beConstructedWith($reflect);
        $this->reflect = $reflect;
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('InCache\Code\Analyzer');
    }

    public function it_should_scan_file()
    {
        $this->reflect->scan($this->getFixturePath('data'))->willReturn($this);
        $this->reflect->getClasses()->willReturn($this->includeFixture('data'));

        $this->setFile($this->getFixturePath('data'))->shouldReturn($this);
        $this->scan();
    }

    public function it_should_analyze_class_data()
    {
        $this->it_should_scan_file();

        $this->getNamespace()->shouldReturn('Vendor');
        $this->getClassName()->shouldReturn('TestClass');
        $this->getMethodSignature('testMethod')->shouldReturn('testMethod($a = 1)');
        $this->getMethodVisibility('testMethod')->shouldReturn('public');
        $this->getParent('testMethod')->shouldReturn('SomeParent');
        $this->getInterfaces('testMethod')->shouldReturn('SomeInterface1, SomeInterface2');
    }
}
