<?php

namespace swede2k\XF2Bridge\Facades;

use Illuminate\Support\Facades\Facade;

class XF2Bridge extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'xf2bridge';
    }
}