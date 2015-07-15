<?php

/**
 * Created by PhpStorm.
 * User: Hieu Le
 * Date: 7/15/2015
 * Time: 5:45 PM
 */
class Auth
{
    /**
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * Attempt to authenticate a user using the given credentials.
     *
     * @param array $credentials
     * @param bool  $remember
     * @param bool  $login
     *
     * @return bool
     */
    public function attempt(array $credentials = [], $remember = false, $login = true)
    {
        if (isset($credentials[config('taki.field.both')]))
        {
            $login = $credentials[config('taki.field.both')];
            unset($credentials[config('taki.field.both')]);
            if (isset($credentials[config('taki.field.username')]))
            {
                unset($credentials[config(['taki.field.username'])]);
            }
            if (isset($credentials[config('taki.field.email')]))
            {
                unset($credentials[config('taki.field.email')]);
            }
            if (filter_var($login, FILTER_VALIDATE_EMAIL))
            {
                $credentials[config('taki.field.email')] = $login;
            } else
            {
                $credentials[config('taki.field.username')] = $login;
            }
        }

        return $this->auth->attempt($credentials, $remember, $login);
    }
}