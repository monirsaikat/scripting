<?php

namespace Src\Providers;

use Src\Models\User;

class UserServiceProvider extends ServiceProvider
{
    public function register(\Pimple\Container $container)
    {


        $container['user'] = function () {
            return new User();
        };

    }
}
