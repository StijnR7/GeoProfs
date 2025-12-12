<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Leave;
use App\Observers\LeaveObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
  public function boot()
{
    Leave::observe(LeaveObserver::class);
}
}
