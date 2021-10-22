<?php

namespace App\Providers;

use App\Repositories\CityRepository;
use App\Repositories\ProvinceRepository;
use App\Repositories\ThreadRepository;
use App\Repositories\TicketRepository;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Infrastructure\Interfaces\CityRepositoryInterface;
use Infrastructure\Interfaces\ProvinceRepositoryInterface;
use Infrastructure\Interfaces\ThreadRepositoryInterface;
use Infrastructure\Interfaces\TicketRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBinding();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if(config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }

    private function registerBinding()
    {
        $this->app->bind(TicketRepositoryInterface::class, TicketRepository::class);
        $this->app->bind(ThreadRepositoryInterface::class, ThreadRepository::class);
        $this->app->bind(CityRepositoryInterface::class, CityRepository::class);
        $this->app->bind(ProvinceRepositoryInterface::class, ProvinceRepository::class);
    }
}
