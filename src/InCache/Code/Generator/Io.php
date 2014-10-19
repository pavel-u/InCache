<?php
/**
 * This file is part of the InCache library. 
 * 
 * (c) Pavel Yukhnevich <ukhnevich@gmail.com>. 
 * 
 * For the full copyright and license information, please view the LICENSE 
 * file that was distributed with this source code.
 */

namespace InCache\Code\Generator;


class Io
{
    /**
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    protected $filesystem;

    /**
     * @param \Symfony\Component\Filesystem\Filesystem $filesystem
     * @param $directory
     */
    public function __construct(
        \Symfony\Component\Filesystem\Filesystem $filesystem,
        $directory
    ) {
        $this->filesystem = $filesystem;
        if (!file_exists($directory)) {
            $this->mkdir($directory);
        }
    }

    /**
     * @param string|array $dirs
     * @param int $mode
     * @throws \InCache\RuntimeException
     */
    public function mkdir($dirs, $mode = 0777)
    {
        try {
            $this->filesystem->mkdir($dirs, $mode);
        } catch (\Exception $e) {
            throw new \InCache\RuntimeException("Failed to create directory.");
        }
    }

    /**
     * @param string $filename
     * @return string
     * @throws \InCache\RuntimeException
     */
    public function fileGetContents($filename)
    {
        $result = @file_get_contents($filename);
        if ($result === false) {
            throw new \InCache\RuntimeException("Failed to read file {$filename}.");
        }
        return $result;
    }

    /**
     * @param string $filename
     * @param string $data
     * @return int
     * @throws \InCache\RuntimeException
     */
    public function filePutContents($filename, $data)
    {
        $result = @file_put_contents($filename, $data);
        if ($result === false) {
            throw new \InCache\RuntimeException("Failed to write file {$filename}.");
        }
        return $result;
    }
}
