<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        //
        Schema::defaultStringLength(191);
        Collection::macro('validate', function (array $rules) {
            /** @var $this Collection */
            return $this->values()->filter(function ($array) use ($rules) {
                return Validator::make($array, $rules)->passes();
            });
        });
    }
}
