<?php
/**
 * This file is part of the InCache library. 
 * 
 * (c) Pavel Yukhnevich <ukhnevich@gmail.com>. 
 * 
 * For the full copyright and license information, please view the LICENSE 
 * file that was distributed with this source code. 

 */
namespace Cache;
function spl_autoload_register($parameter)
{
    return true;
}


function spl_autoload_unregister($parameter)
{
    return true;
}
