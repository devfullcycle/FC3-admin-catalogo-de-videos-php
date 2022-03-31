<?php

namespace App\Providers;

use App\Repositories\Eloquent\{
    CategoryEloquentRepository,
    GenreEloquentRepository
};
use App\Repositories\Transaction\DBTransaction;
use Core\Domain\Repository\{
    CategoryRepositoryInterface,
    GenreRepositoryInterface
};
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
        $this->app->singleton(
            GenreRepositoryInterface::class,
            GenreEloquentRepository::class
        );

        /**
         * DB Transaction
         */
        $this->app->bind(
            TransactionInterface::class,
            DBTransaction::class,
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
