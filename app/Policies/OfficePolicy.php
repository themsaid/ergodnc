<?php

namespace App\Policies;

use App\Models\Office;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OfficePolicy
{
    use HandlesAuthorization;

    public function update(User $user, Office $office)
    {
        return $user->id == $office->user_id;
    }

    public function delete(User $user, Office $office)
    {
        return $user->id == $office->user_id;
    }
}
