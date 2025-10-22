<?php

namespace App\Policies;

use App\Models\Deal;
use App\Models\User;

class DealPolicy
{
    public function view(User $user, Deal $deal)
    {
        return $user->id === $deal->user_id;
    }

    public function update(User $user, Deal $deal)
    {
        return $user->id === $deal->user_id;
    }

    public function delete(User $user, Deal $deal)
    {
        return $user->id === $deal->user_id;
    }
}
