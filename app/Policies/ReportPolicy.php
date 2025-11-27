<?php

namespace App\Policies;

use App\Models\InspectionReport;
use App\Models\User;

class ReportPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, InspectionReport $report): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, InspectionReport $report): bool
    {
        return $user->isAdmin() || $report->created_by === $user->id;
    }

    public function delete(User $user, InspectionReport $report): bool
    {
        return $user->isAdmin();
    }

    public function restore(User $user, InspectionReport $report): bool
    {
        return $user->isAdmin();
    }

    public function forceDelete(User $user, InspectionReport $report): bool
    {
        return $user->isAdmin();
    }
}
