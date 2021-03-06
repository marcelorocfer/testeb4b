<?php

namespace App\Services;

use App\Models\User;

class AllUsersService
{
    protected $user;
    
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getAllUsers()
    {
        return $this->user->all();
    }
}
