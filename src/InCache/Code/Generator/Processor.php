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


interface Processor
{
    /**
     * Set list of methods which are going to be processed
     * @param array $methods
     * @return $this
     */
    public function setMethods(array $methods);

    /**
     * Return processed result
     *
     * @param string $file
     * @return string
     * @throws \InCache\UnexpectedValueException
     */
    public function process($file);

    /**
     * Returns new class suffix
     *
     * @return string
     */
    public function getSuffix();
}