<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use App\Repositories\CityRepository;
use App\Repositories\ThreadRepository;
use App\Repositories\TicketRepository;
use App\Services\Ticket\TicketService;
use Illuminate\Support\ServiceProvider;
use App\Repositories\ProvinceRepository;
use Infrastructure\Interfaces\CityRepositoryInterface;
use Infrastructure\Interfaces\ThreadRepositoryInterface;
use Infrastructure\Interfaces\TicketRepositoryInterface;
use Infrastructure\Interfaces\ProvinceRepositoryInterface;
use Infrastructure\Interfaces\Services\TicketServiceInterface;

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
        $this->app->bind(TicketServiceInterface::class, TicketService::class);
    }
}
