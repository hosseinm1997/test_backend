<?php

namespace App\Providers;

use App\Repositories\ThreadRepository;
use App\Repositories\TicketRepository;
use Illuminate\Support\ServiceProvider;
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
        //
    }

    private function registerBinding()
    {
        $this->app->bind(TicketRepositoryInterface::class, TicketRepository::class);
        $this->app->bind(ThreadRepositoryInterface::class, ThreadRepository::class);
    }
}
