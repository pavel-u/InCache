<?php
/**
 * This file is part of the InCache library. 
 * 
 * (c) Pavel Yukhnevich <ukhnevich@gmail.com>. 
 * 
 * For the full copyright and license information, please view the LICENSE 
 * file that was distributed with this source code.
 */

namespace InCache;


class ObjectBehavior extends \PhpSpec\ObjectBehavior
{
    protected function getFixturePath($name)
    {
        return realpath(__DIR__ . DIRECTORY_SEPARATOR
            . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR
            . str_replace('\\', DIRECTORY_SEPARATOR,  get_class($this))
            . DIRECTORY_SEPARATOR . $name . '.fixture');
    }

    protected function getFixture($name)
    {
        return file_get_contents($this->getFixturePath($name));
    }

    protected function includeFixture($name)
    {
        return include $this->getFixturePath($name);
    }
} 