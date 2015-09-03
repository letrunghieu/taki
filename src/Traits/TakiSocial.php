<?php

namespace HieuLe\Taki\Traits;

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
}
