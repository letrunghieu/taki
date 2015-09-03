<?php

namespace HieuLe\Taki\Traits;

/**
 * Common trait of Taki using all available traits
 *
 * @package HieuLe\Taki
 */
trait TakiTrait
{

    use TakiAuthentication,
        TakiRegistration,
        TakiSocial {
        TakiAuthentication::redirectPath insteadof TakiRegistration;
    }
}
