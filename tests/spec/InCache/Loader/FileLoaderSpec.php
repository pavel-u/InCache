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

class FileLoaderSpec extends \InCache\ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('InCache\Loader\FileLoader');
    }

    public function it_should_include_file()
    {
        $this->includeFile($this->getFixturePath('file'));
    }

    public function it_should_throw_on_invalid_file()
    {
        $this->shouldThrow('\InCache\RuntimeException')->duringIncludeFile('');
    }
}
