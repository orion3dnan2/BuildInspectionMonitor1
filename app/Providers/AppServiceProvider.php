<?php

namespace App\Providers;

use App\Models\InspectionReport;
use App\Models\User;
use App\Policies\ReportPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(InspectionReport::class, ReportPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
    }
}
