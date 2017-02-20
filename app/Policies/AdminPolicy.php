<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization;

    
    public function admin(User $user, $abilities)
    {
        return $user->isAdmin();
    }
}
