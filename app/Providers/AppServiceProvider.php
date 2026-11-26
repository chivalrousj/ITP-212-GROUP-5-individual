<?php

namespace App\Providers;

use App\Models\Announcement;
use App\Services\WeatherService;
use Illuminate\Support\Facades\View;
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
        // Weather widget: the dashboards never call the API themselves
        View::composer('partials.weather', function ($view) {
            $service = app(WeatherService::class);
            $weather = $service->current();

            $view->with('weather', $weather)
                 ->with('weatherLabel', $service->label(data_get($weather, 'weather_code')));
        });

        // The three newest announcements for the Officer, Adviser and Student dashboards
        View::composer('partials.latest-announcements', function ($view) {
            $view->with('latestAnnouncements', Announcement::latest()->take(3)->get());
        });

        // Gates: general "may this user do this?" rules, based on role

        // Only Officers manage the SSG system
        Gate::define('manage-ssg', fn (User $user) => $user->hasRole('Officer'));

        // Officers and Advisers can view reports
        Gate::define('view-reports', fn (User $user) => $user->hasRole('Officer', 'Adviser'));

        // Only Students use check-in and check-out
        Gate::define('use-attendance', fn (User $user) => $user->hasRole('Student'));
    }
}