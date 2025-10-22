<?php

namespace App\Http\Controllers\Concerns;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait HasAuthenticatedUser
{
    protected function getAuthenticatedUser(): User
    {
        /** @var User $user */
        $user = Auth::user();
        return $user;
    }
}
