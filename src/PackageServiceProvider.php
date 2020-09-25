<?php
/*
 * @created 23/09/2020 - 10:33 PM
 * @project log-package
 * @author Aekansh Partani
*/

namespace Rigits\LaravelLogLens;

use Illuminate\Support\ServiceProvider;

class PackageServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {

        if (method_exists($this, 'loadViewsFrom')) {
            $this->loadViewsFrom(__DIR__ . '/../views', 'log-lens');
        }
    }

}
