<?php

namespace HieuLe\Taki\Stubs;

use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use HieuLe\Taki\Traits\TakiTrait;

/**
 * A stub controller
 *
 * @author Hieu Le <letrunghieu.cse09@gmail.com>
 */
class NoThrottleController
{

    use ValidatesRequests,
        TakiTrait;

    protected function throwValidationException(Request $request, $validator)
    {
        throw new HttpResponseException(new \Illuminate\Http\RedirectResponse('foo'));
    }

    protected function getLockoutErrorMessage($seconds)
    {
        return '';
    }

    protected function getFailedLoginMessage()
    {
        return '';
    }
}
