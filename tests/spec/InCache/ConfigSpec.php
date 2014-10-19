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

class ConfigSpec extends \InCache\ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith($this->getFixturePath('cache.xml'));
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('InCache\Config');
    }

    public function it_should_return_classes_data()
    {
        $this->getClasses()->shouldReturn($this->includeFixture('expected'));
    }

    public function it_should_retrieve_value_from_config()
    {
        $this->getValue('cacheDir')->shouldReturn('/var/www/cache/_cache');
    }
}
