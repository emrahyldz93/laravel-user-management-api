<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\MailService;
use App\Services\ExternalMailServiceAdapter;

class MailServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
