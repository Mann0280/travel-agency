<?php
// app/Helpers/AuthHelper.php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class AuthHelper
{
    public static function isLoggedIn()
    {
        return Auth::check();
    }

    public static function requireLogin()
    {
        if (!self::isLoggedIn()) {
            return redirect()->route('login');
        }
    }

    public static function getUserName()
    {
        return Auth::check() ? Auth::user()->name : 'Guest';
    }

    public static function getUserEmail()
    {
        return Auth::check() ? Auth::user()->email : '';
    }

    public static function getUserPhone()
    {
        return Auth::check() ? Auth::user()->phone : '';
    }
}