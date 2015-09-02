<?php

namespace HieuLe\Taki\Stubs;

use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use HieuLe\Taki\Traits\TakiTrait;

/**
 * A stub controller
 *
 * @author Hieu Le <letrunghieu.cse09@gmail.com>
 */
class Controller
{

    use ValidatesRequests,
        ThrottlesLogins,
        TakiTrait;

    protected function throwValidationException(Request $request, $validator)
    {
        throw new HttpResponseException(new \Illuminate\Http\RedirectResponse('foo'));
    }
}
