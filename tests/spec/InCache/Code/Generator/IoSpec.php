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

class IoSpec extends \InCache\ObjectBehavior
{
    /**
     * @var \PhpSpec\Wrapper\Collaborator
     */
    protected $filesystem;

    /**
     * @param \PhpSpec\Wrapper\Collaborator $filesystem
     */
    public function let(\PhpSpec\Wrapper\Collaborator $filesystem)
    {
        $filesystem->beADoubleOf('\Symfony\Component\Filesystem\Filesystem');
        $this->beConstructedWith($filesystem, __DIR__);
        $this->filesystem = $filesystem;
        file_put_contents($this->getFixturePath('content'), 'Some content 1');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('InCache\Code\Generator\Io');
    }

    public function it_should_create_valid_directory()
    {
        $this->filesystem->mkdir(__DIR__, 0777)->willReturn(null);
        $this->mkdir(__DIR__);
    }

    public function it_should_throw_on_invalid_directory_creation()
    {
        $this->filesystem->mkdir('', 0777)->willReturn(null);
        $this->shouldThrow('\InCache\RuntimeException')->duringMkdir(__DIR__);
    }

    public function it_should_get_file_contents()
    {
        $this->fileGetContents($this->getFixturePath('content'))->shouldReturn('Some content 1');
    }

    public function it_should_throw_on_get_invalid_file_contents()
    {
        $this->shouldThrow('\InCache\RuntimeException')->duringFileGetContents('lol');
    }

    public function it_should_put_contents_into_file()
    {
        $this->filePutContents($this->getFixturePath('content'), 'Some test content')
            ->shouldPutContentIntoFile($this->getFixturePath('content'), 'Some test content');
    }

    public function getMatchers()
    {
        return [
            'putContentIntoFile' => function($subject, $filename, $contents) {
                return file_get_contents($filename) === $contents;
            }
        ];
    }
}
