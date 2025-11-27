<?php

namespace App\Policies;

use App\Models\Record;
use App\Models\User;

class RecordPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Record $record): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->canCreateRecords();
    }

    public function update(User $user, Record $record): bool
    {
        return $user->canEditRecords();
    }

    public function delete(User $user, Record $record): bool
    {
        return $user->canDeleteRecords();
    }

    public function restore(User $user, Record $record): bool
    {
        return $user->isAdmin();
    }

    public function forceDelete(User $user, Record $record): bool
    {
        return $user->isAdmin();
    }
}
