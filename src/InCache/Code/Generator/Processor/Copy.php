<?php
/**
 * This file is part of the InCache library. 
 * 
 * (c) Pavel Yukhnevich <ukhnevich@gmail.com>. 
 * 
 * For the full copyright and license information, please view the LICENSE 
 * file that was distributed with this source code.
 */

namespace InCache\Code\Generator\Processor;


class Copy implements \InCache\Code\Generator\Processor
{
    const SUFFIX_VALUE = '__Copy';

    /**
     * {@inheritdoc}
     */
    public function setMethods(array $options = [])
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function process($sourcePath)
    {
        return preg_replace(
            '/<\?php.*class\s*(?<className>.*?[^(\n|\s)]*)/s', '${0}' . self::SUFFIX_VALUE,
            file_get_contents($sourcePath)
        );
    }

    /**
     * @return string
     */
    public function getSuffix()
    {
        return self::SUFFIX_VALUE;
    }
}
