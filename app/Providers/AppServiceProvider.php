<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Gates: general "may this user do this?" rules, based on role

        // Only Officers manage the SSG system
        Gate::define('manage-ssg', fn (User $user) => $user->hasRole('Officer'));

        // Officers and Advisers can view reports
        Gate::define('view-reports', fn (User $user) => $user->hasRole('Officer', 'Adviser'));

        // Only Students use check-in and check-out
        Gate::define('use-attendance', fn (User $user) => $user->hasRole('Student'));
    }
}