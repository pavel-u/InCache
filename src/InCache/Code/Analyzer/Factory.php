<?php
/**
 * This file is part of the InCache library. 
 * 
 * (c) Pavel Yukhnevich <ukhnevich@gmail.com>. 
 * 
 * For the full copyright and license information, please view the LICENSE 
 * file that was distributed with this source code.
 */

namespace InCache\Code\Analyzer;


class Factory
{

    /**
     * @param array $options
     * @return \InCache\Code\Analyzer
     */
    public function create(array $options = [])
    {
        $options = array_merge([
            'properties' => [
                'interface' => [
                    'parent', 'methods'
                ],
                'class' => [
                    'parent', 'methods', 'interfaces', 'package'
                ],
                'function' => [
                    'signature', 'visibility'
                ],
            ]
        ], $options);

        return new \InCache\Code\Analyzer(
            new \PHP_Reflect($options)
        );
    }
}
