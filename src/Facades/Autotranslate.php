<?php

namespace BernskioldMedia\Autotranslate\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \BernskioldMedia\Autotranslate\Autotranslate
 */
class Autotranslate extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \BernskioldMedia\Autotranslate\Autotranslate::class;
    }
}
