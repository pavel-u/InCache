<?php
/**
 * This file is part of the InCache library. 
 * 
 * (c) Pavel Yukhnevich <ukhnevich@gmail.com>. 
 * 
 * For the full copyright and license information, please view the LICENSE 
 * file that was distributed with this source code. 

 */

namespace InCache\Loader;


class FileLoader
{
    /**
     * @param string $file
     * @throws \InCache\RuntimeException
     */
    public function includeFile($file)
    {
        if (!file_exists($file) || !is_file($file)) {
            throw new \InCache\RuntimeException("Can't load '{$file}' file'");
        }
        include $file;
    }
}
