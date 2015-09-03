<?php

namespace HieuLe\Taki\Stubs;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\CanResetPassword;

/**
 * Description of User
 *
 * @author Hieu Le <hieu@codeforcevina.com>
 */
class User implements Authenticatable, Authorizable, CanResetPassword
{
    
    public static $first = true;

    public function can($ability, $arguments = array())
    {
        
    }

    public function getAuthIdentifier()
    {
        
    }

    public function getAuthPassword()
    {
        
    }

    public function getEmailForPasswordReset()
    {
        
    }

    public function getRememberToken()
    {
        
    }

    public function getRememberTokenName()
    {
        
    }

    public function setRememberToken($value)
    {
        
    }

    public static function save()
    {
        return true;
    }

    public static function where($a, $b)
    {
        return new static;
    }

    public function first()
    {
        return static::$first;
    }
}
