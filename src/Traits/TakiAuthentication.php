<?php

namespace HieuLe\Taki;

use Illuminate\Support\Facades\Validator;
/**
 * Taki authentication trait
 *
 * @package HieuLe\Taki
 */
trait TakiAuthentication
{
    /**
     * Validate the user information when creating user
     *
     * @param array $data
     *
     * @return \Illuminate\Validation\Validator
     */
    protected function validateCreating(array $data)
    {
        $rules = config('taki.validator.create', []);

        if (config('taki.username.required'))
        {
            $rules[config('taki.field.username')] = config('taki.username.validator', 'required');
        }

        return Validator::make($data, $rules);
    }

    /**
     * Validate user information when updating user
     *
     * @param array $data
     *
     * @return \Illuminate\Validation\Validator
     */
    protected function validateUpdating(array $data)
    {
        $rules = config('taki.validator.update', []);

        return Validator::make($data, $rules);
    }

}