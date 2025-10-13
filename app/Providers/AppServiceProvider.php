<?php

namespace App\Providers;

use App\Repositories\Eloquent\AuditRepository as EloquentAuditRepository;
use App\Repositories\Eloquent\BudgetRepository;
use App\Repositories\Eloquent\ContactRepository;
use App\Repositories\Eloquent\DashboardRepository;
use App\Repositories\Eloquent\DebtRepository;
use App\Repositories\Eloquent\ImageRepository;
use App\Repositories\Eloquent\PayRepository;
use App\Repositories\Eloquent\TransactionRepository;
use App\Repositories\Interfaces\AuditRepositoryInterface;
use App\Repositories\Interfaces\BudgetRepositoryInterface;
use App\Repositories\Interfaces\ContactRepositoryInterface;
use App\Repositories\Interfaces\DashboardRepositoryInterface;
use App\Repositories\Interfaces\DebtRepositoryInterface;
use App\Repositories\Interfaces\ImageRepositoryInterface;
use App\Repositories\Interfaces\PayRepositoryInterface;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Services\local\ImageServiceLocal;
use App\Services\Interfaces\ImageServiceInterface;
use AuditRepository;
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
        $this->app->bind(DebtRepositoryInterface::class, DebtRepository::class);
        $this->app->bind(PayRepositoryInterface::class, PayRepository::class);
        $this->app->bind(ImageServiceInterface::class, ImageServiceLocal::class);
        $this->app->bind(ImageRepositoryInterface::class, ImageRepository::class);
        $this->app->bind(AuditRepositoryInterface::class, EloquentAuditRepository::class);
        $this->app->bind(DashboardRepositoryInterface::class, DashboardRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
