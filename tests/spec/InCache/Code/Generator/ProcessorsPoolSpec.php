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

class ProcessorsPoolSpec extends \InCache\ObjectBehavior
{
    /**
     * @var \PhpSpec\Wrapper\Collaborator
     */
    protected $filesystem;

    public function it_is_initializable()
    {
        $this->shouldHaveType('InCache\Code\Generator\ProcessorsPool');
    }

    public function it_should_take_file_path_and_methods()
    {
        $this->setFilePath('')
            ->setMethods([])
            ->shouldReturn($this);
    }

    public function it_should_apply_formatters()
    {
        $this->process()->shouldBeAnInstanceOf('Generator');
    }
}
