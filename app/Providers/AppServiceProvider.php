<?php

namespace App\Providers;

use App\Repositories\Eloquent\BudgetRepository;
use App\Repositories\Eloquent\ContactRepository;
use App\Repositories\Eloquent\TransactionRepository;
use App\Repositories\Interfaces\BudgetRepositoryInterface;
use App\Repositories\Interfaces\ContactRepositoryInterface;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ContactRepositoryInterface::class, ContactRepository::class);
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);
        $this->app->bind(BudgetRepositoryInterface::class, BudgetRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
