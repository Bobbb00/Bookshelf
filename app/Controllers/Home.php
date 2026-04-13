<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        if (service('authentication')->check()) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    public function register(): string
    {
        return view('auth/register');
    }

    public function dashboard(): string
    {
        return view('user/index');
    }
}
