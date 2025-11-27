<?php

namespace App\Providers;

use App\Models\Record;
use App\Models\User;
use App\Policies\RecordPolicy;
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
        Gate::policy(Record::class, RecordPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
    }
}
