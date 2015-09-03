<?php

namespace HieuLe\Taki;

use Illuminate\Auth\AuthManager;
use Illuminate\Session\Store;

/**
 * Created by PhpStorm.
 * User: Hieu Le
 * Date: 7/15/2015
 * Time: 5:45 PM
 */
class Auth
{

    /**
     * @var AuthManager
     */
    protected $auth;

    /**
     *
     * @var Store 
     */
    protected $session;

    function __construct(AuthManager $auth, Store $store)
    {
        $this->auth    = $auth;
        $this->session = $store;
    }

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
        // we do thing if the user is allowed to login with only username or
        // only password
        if ((config('taki.login_by') == 'both') && isset($credentials[config('taki.field.both')])) {
            $login = $credentials[config('taki.field.both')];
            unset($credentials[config('taki.field.both')]);
            if (isset($credentials[config('taki.field.username')])) {
                unset($credentials[config(['taki.field.username'])]);
            }
            if (isset($credentials[config('taki.field.email')])) {
                unset($credentials[config('taki.field.email')]);
            }
            if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
                $credentials[config('taki.field.email')] = $login;
            } else {
                $credentials[config('taki.field.username')] = $login;
            }
        }

        return $this->auth->attempt($credentials, $remember, $login);
    }

    public function saveOauthUser($provider, $email)
    {
        $this->session->put("{$provider}_{$email}", \Carbon\Carbon::now());
    }

    public function checkOauthUser($provider, $email)
    {
        return $this->session->has("{$provider}_{$email}");
    }

    public function clearOauthUser($provider, $email)
    {
        $this->session->forget("{$provider}_{$email}");
    }
}
