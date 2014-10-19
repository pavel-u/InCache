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

class CopySpec extends \InCache\ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('InCache\Code\Generator\Processor\Copy');
    }


    public function it_should_rename_class_name()
    {
        $this->process($this->getFixturePath('source'))
            ->shouldReturn($this->getFixture('expected'));
    }

    public function it_should_return_suffix()
    {
        $this->getSuffix()->shouldReturn(\InCache\Code\Generator\Processor\Copy::SUFFIX_VALUE);
    }
}
