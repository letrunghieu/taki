<?php
namespace HieuLe\Taki\Traits;

/**
 * Common trait of Taki using all available traits
 *
 * @package HieuLe\Taki
 */
trait TakiTrait
{
    use TakiAuthentication, TakiRegistration {
        TakiAuthentication::redirectPath insteadof TakiRegistration;
    }
}