<?php

namespace Niel\CollectiveAdapter\Facades;

use Illuminate\Support\Facades\Facade;

class Form extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'form';  // The key we registered in the ServiceProvider
    }
}
