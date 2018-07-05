<?php

namespace App\Providers;


use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191); //Solved by increasing StringLength

//        \View::share(['channels' => \App\Channel::all()]);
//        \View::composer('*', function ($view) {
//
////            $channels = Cache::rememberForever('channels', function () {
////                return \App\Channel::all();
////            });
//
//            $view->with(['channels' => \App\Channel::all()]);
//
//
//        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        if(app()->isLocal())
        {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }
    }
}
