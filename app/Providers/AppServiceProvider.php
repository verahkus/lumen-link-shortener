<?php

namespace App\Providers;

use App\Rules\UrlRule;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    public function boot() {
        UrlRule::validate();
    }
}
