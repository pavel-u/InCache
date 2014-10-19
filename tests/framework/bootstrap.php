<?php
/**
 * This file is part of the InCache library. 
 * 
 * (c) Pavel Yukhnevich <ukhnevich@gmail.com>. 
 * 
 * For the full copyright and license information, please view the LICENSE 
 * file that was distributed with this source code. 

 */

include_once __DIR__ . DIRECTORY_SEPARATOR . 'autoload_functions.php';

/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require __DIR__ . '/../../vendor/autoload.php';
$loader->add('InCache\\', __DIR__);
new \InCache\ObjectBehavior;