<?php

{{namespace}}

class {{className}} extends {{extends}}
{
    /**
     * @var \InCache\PoolFactory
     */
    protected $poolFactory;

    public function __construct()
    {
        $this->poolFactory = \InCache\PoolFactory::instance();
        return call_user_func_array(array('parent', '__construct'), func_get_args());
    }
    {{methods}}
}