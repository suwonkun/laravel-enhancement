<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Company;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function before(User $user, $ability)
    {
        if ($user->is_admin) {
            return true;
        }
    }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user, Company $company): bool
    {
        return $user->company_id === $company->id;
    }

}
