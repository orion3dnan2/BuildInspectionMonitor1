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
        return true;
    }

    public function update(User $user, Record $record): bool
    {
        return true;
    }

    public function delete(User $user, Record $record): bool
    {
        return $user->isAdmin();
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
