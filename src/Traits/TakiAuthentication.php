<?php

trait TakiAuthentication
{
    protected function validateCreating(array $data)
    {
        $rules = config('taki.validator.create', []);

        if (config('taki.username.required'))
        {
            $rules[config('taki.field.username')] = config('taki.username.validator', 'required');
        }

        return Validator::make($data, $rules);
    }

    protected function validateUpdating(array $data)
    {
        $rules = config('taki.validator.update', []);

        return Validator::make($data, $rules);
    }


}