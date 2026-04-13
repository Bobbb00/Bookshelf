<?php

namespace Config;

class Auth extends \Myth\Auth\Config\Auth
{
    /**
     * Redirect destination after successful login.
     */
    public $landingRoute = '/dashboard';
}
