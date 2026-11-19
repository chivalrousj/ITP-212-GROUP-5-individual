<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Only an Admin can delete a user, and never their own account.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->hasRole('Admin') && $user->id !== $model->id;
    }
}