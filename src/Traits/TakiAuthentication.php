<?php

namespace HieuLe\Taki\Traits;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

/**
 * Taki authentication trait
 *
 * @package HieuLe\Taki
 */
trait TakiAuthentication
{

    use AuthenticatesUsers;

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
        $this->validate($request, [
            $this->loginUsername() => 'required',
            'password'             => 'required',
        ]);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $throttles = $this->isUsingThrottlesLoginsTrait();

        if ($throttles && $this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->getCredentials($request);

        if (\Taki::attempt($credentials, $request->has('remember'))) {
            return $this->handleUserWasAuthenticated($request, $throttles);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if ($throttles) {
            $this->incrementLoginAttempts($request);
        }

        return redirect($this->loginPath())
                ->withInput($request->only($this->loginUsername(), 'remember'))
                ->withErrors([
                    $this->loginUsername() => $this->getFailedLoginMessage(),
        ]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function loginUsername()
    {
        $loginBy    = config('taki.login_by');
        $loginField = config('taki.field.both');
        if ($loginBy === 'email') {
            $loginField = config('taki.field.email');
        } elseif ($loginBy === 'username') {
            $loginField = config('taki.field.username');
        }

        return $loginField;
    }
}
