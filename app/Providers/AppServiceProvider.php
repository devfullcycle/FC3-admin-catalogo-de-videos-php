<?php

namespace App\Providers;

use App\Repositories\Eloquent\{
    CategoryEloquentRepository
};
use App\Repositories\Transaction\DBTransation;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\UseCase\Interfaces\TransactionInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            CategoryRepositoryInterface::class,
            CategoryEloquentRepository::class
        );

        /**
         * DB Transaction
         */
        $this->app->bind(
            TransactionInterface::class,
            DBTransation::class,
        );
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
}
