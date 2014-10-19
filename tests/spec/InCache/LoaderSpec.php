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

class LoaderSpec extends \InCache\ObjectBehavior
{
    /**
     * @var \PhpSpec\Wrapper\Collaborator
     */
    protected $parentLoader;


    /**
     * @var \PhpSpec\Wrapper\Collaborator
     */
    protected $fileLoader;


    /**
     * @var \PhpSpec\Wrapper\Collaborator
     */
    protected $config;

    /**
     * @param \PhpSpec\Wrapper\Collaborator $parentLoader
     * @param \PhpSpec\Wrapper\Collaborator $fileLoader
     * @param \PhpSpec\Wrapper\Collaborator $config
     */
    public function let(
        \PhpSpec\Wrapper\Collaborator $parentLoader,
        \PhpSpec\Wrapper\Collaborator $fileLoader,
        \PhpSpec\Wrapper\Collaborator $config
    )
    {
        $parentLoader->beADoubleOf('\Composer\Autoload\ClassLoader');
        $fileLoader->beADoubleOf('\InCache\Loader\FileLoader');
        $config->beADoubleOf('\InCache\Config');
        $this->beConstructedWith($parentLoader, $fileLoader, $config);

        $this->parentLoader = $parentLoader;
        $this->fileLoader = $fileLoader;
        $this->config = $config;
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('InCache\Loader');
    }

    public function it_should_find_file_by_class()
    {
        $this->parentLoader->findFile('SomeClass')->willReturn('lol');
        $this->findFile('SomeClass')->shouldReturn('lol');
    }

    public function it_should_load_class()
    {
        $this->parentLoader->findFile('SomeClass')->willReturn('lol');
        $this->fileLoader->includeFile('lol')->willReturn(null);
        $this->loadClass('SomeClass');

        $this->parentLoader->findFile('\InCache\SomeInternalClass')->willReturn('lol2');
        $this->fileLoader->includeFile('lol2')->willReturn(null);
        $this->loadClass('\InCache\SomeInternalClass');
    }

}
