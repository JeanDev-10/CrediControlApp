<?php

namespace App\Services;


class UserService
{
    public function getUserLoggedIn()
    {
        return auth()->user();
    }
}