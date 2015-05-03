<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Response;

class CorsServiceProvider extends ServiceProvider
{

    /**
     * Register OPTIONS route to any requests
     */
    public function register()
    {
        /** @var \Illuminate\Http\Request $request */
        $request = $this->app->make('request');
        
        $this->app->options($request->path(), function(){
            $r = new Response('OK', 200);
            // $r->header("Access-Control-Allow-Origin", "*");
            return $r;
            // return new Response('OK', 200);
        });
    }
}
