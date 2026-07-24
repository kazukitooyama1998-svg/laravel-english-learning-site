<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Services\English\XpService;
use App\Services\English\StreakService;
use App\Services\English\StudyLogService;
use App\Services\English\ProgressService;
use App\Services\English\RankingService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // 英語学習 Service はシングルトンとして登録
        $this->app->singleton(XpService::class);
        $this->app->singleton(StreakService::class);
        $this->app->singleton(StudyLogService::class, function ($app) {
            return new StudyLogService($app->make(StreakService::class));
        });
        $this->app->singleton(ProgressService::class);
        $this->app->singleton(RankingService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        Gate::define('admin', function($user){
            return $user->role_id === User::ADMIN_ROLE_ID;
        });
    }
}
