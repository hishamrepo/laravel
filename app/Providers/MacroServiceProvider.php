<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class MacroServiceProvider extends ServiceProvider
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
        Response::macro('api', function ($status, $message = '', $payload = []) {
            return Response::json([
                'status' => $status >= 200 && $status < 400,
                'message' => $message,
                'lang' => app()->getLocale(),
                'payload' => $payload,
            ])->header('Content-Type', 'application/json')
                ->setStatusCode($status, $message);
        });
    }
}
