<?php

namespace HieuLe\Taki\Traits;

use Illuminate\Support\Facades\Auth;

/**
 * Description of TakiSocial
 *
 * @author Hieu Le <hieu@codeforcevina.com>
 */
trait TakiSocial
{

    /**
     * Redirect to the service authentication page
     * 
     * @param string $service
     * @return \Illuminate\Http\Response
     */
    public function getOauth($service)
    {
        return \Socialite::driver($service)->redirect();
    }

    /**
     * Create user or log the user in after success authentication
     * 
     * @param type $service
     * @return type
     */
    public function getOauthCallback($service)
    {
        $user = \Socialite::driver($service)->user();

        $email = $user->getEmail();

        $dbUser = \User::where('email', $email)->first();
        if (!$dbUser) {
            $userInfo = [
                'name'                     => $user->getName(),
                config('taki.field.email') => $user->getEmail(),
                'avatar'                   => $user->getAvatar(),
            ];

            if (config('taki.social.password_required') || !config('taki.social.username_auto')) {
                \Taki::saveOauthUser($service, $user->getEmail());
                return redirect($this->getOauthCompletePath())->with($userInfo);
            } else {
                $userInfo['password']                    = false;
                $userInfo['token']                       = false;
                $userInfo[config('taki.field.username')] = $this->generateUsername($service, $user);
                $dbUser                                  = $this->create($userInfo);
            }
        }

        Auth::login($dbUser, true);
        return redirect()->intended();
    }

    protected function getOauthCompletePath()
    {
        return property_exists($this, 'oauthCompletedPath') ? $this->oauthCompletedPath : '/oauth/complete';
    }

    protected function generateUsername($service, $user)
    {
        throw new \RuntimeException('When Taki `social.username_auto` option is set to false, '
        . 'your controller must implement `generateUsername($service, $user)` method and override this one');
    }
}
