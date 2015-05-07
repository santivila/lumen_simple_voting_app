<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Response;

class CorsServiceProvider extends ServiceProvider
{
    /**
     * Register OPTIONS route to any Json requests
     */
    public function register()
    {
        /** @var \Illuminate\Http\Request $request */
        $request = $this->app->make('request');
        if($request->method()=="OPTIONS")
        {
            $this->app->options($request->path(), function(){
                $r = new Response('OK', 200);
                return $r;
            });
        }
    }
}
