<?php

namespace Config;

class Auth extends \Myth\Auth\Config\Auth
{
    /**
     * Redirect destination after successful login.
     */
    public $landingRoute = '/dashboard';

    /**
     * Nama grup default untuk user yang baru mendaftar
     */
    public $defaultUserGroup = 'user';

    public $requireActivation = null; // menghilangan aktivasi lewat email setelah regis

    public $activeResetter = null; // menghilangan aktivasi reset password

    /**
     * Disable password validators due to compatibility issue with CI4.3+ 
     * (CodeIgniter\Entity vs CodeIgniter\Entity\Entity)
     */
    public $passwordValidators = [];

    /**
     * ==========================================
     * OVERRIDE TAMPILAN (VIEW)
     * ==========================================
     * Mengarahkan route login & register ke file view buatan Anda sendiri 
     * yang terletak di folder: app/Views/auth/
     */
    public $views = [
        'login'           => 'auth/login',
        'register'        => 'auth/register',
        'forgot'          => 'Myth\Auth\Views\forgot',
        'reset'           => 'Myth\Auth\Views\reset',
        'emailForgot'     => 'Myth\Auth\Views\emails\forgot',
        'emailActivation' => 'Myth\Auth\Views\emails\activation',
    ];
}

