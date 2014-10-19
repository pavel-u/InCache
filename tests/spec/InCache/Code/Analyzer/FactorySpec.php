<?php
/**
 * This file is part of the InCache library. 
 * 
 * (c) Pavel Yukhnevich <ukhnevich@gmail.com>. 
 * 
 * For the full copyright and license information, please view the LICENSE 
 * file that was distributed with this source code.
 */

namespace spec\InCache\Code\Analyzer;

class FactorySpec extends \InCache\ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('InCache\Code\Analyzer\Factory');
    }

    public function it_should_create_generator()
    {
        $this->create()->shouldBeAnInstanceOf('InCache\Code\Analyzer');
    }
}
