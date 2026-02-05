<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Check if user is admin.
     */
    public function isAdmin(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Check if user is petugas.
     */
    public function isPetugas(User $user): bool
    {
        return $user->isPetugas();
    }

    /**
     * Check if user is regular user.
     */
    public function isUser(User $user): bool
    {
        return $user->isUser();
    }
}
