<?php
/**
 * Created by PhpStorm.
 * User: Hieu Le
 * Date: 7/18/2015
 * Time: 3:28 PM
 */

namespace HieuLe\Taki;


use Illuminate\Support\Facades\Facade;

class TakiFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'taki';
    }
}