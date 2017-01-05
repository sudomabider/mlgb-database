<?php
/**
 * Created by PhpStorm.
 * User: veoc
 * Date: 5/01/17
 * Time: 8:35 PM
 */

namespace App\Providers\ClashOfClans;


use Illuminate\Support\ServiceProvider;

class ClashOfClansServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton([Client::class => 'clashofclans.client'], function() {
            return new Client(config('services.coc.api_token'), config('services.coc.clan_tag'));
        });
    }

    public function provides()
    {
        return [Client::class];
    }
}