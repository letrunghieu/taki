<?php
/**
 * Taki configuration file.
 *
 * This is the default configuration of the package. You need to publish these
 * config file to your application and make modifications on that.
 *
 * User: Hieu Le
 * Date: 7/15/2015
 * Time: 1:42 PM
 */
return [


    'username'              => [
        /*
         * Is the `username` field required when registering new account?
         */
        'required'  => false,
        /**
         * Validate a username
         */
        'validator' => 'required|min:3|max:50',
    ],
    /*
     * Which field is used when authenticating user.
     *
     * Available values: email, username, both
     * Default value: both
     */
    'login_by'              => 'email',
    /*
     * Does user need to confirm their email after creating account, if the
     * email do not need to verified, they will be logged in right after
     * registered.
     */
    'confirm_after_created' => false,
    'field'                 => [

        /*
         * The name of the `username` field
         */
        'username' => 'username',
        /*
         * The name of the `email` field
         */
        'email'    => 'email',
        /*
         * The name of the key of input array when user want to login by username or email
         */
        'both'     => 'login',
    ],
    'social'                => [
        /*
         * If the password is required, after successfully authenticated from
         * third party social providers, user need to provide password before
         * their account can be created.
         *
         * Otherwise, they can be logged in with the new account without the
         * password. They can provide password later if they want to login
         * with the email.
         */
        'password_required' => false,
        /*
         * Generate the username automatically from the user information from
         * social provider or force user to enter their username
         */
        'username_auto'     => true,
    ],
    'emails'                => [
        /*
         * The view name of account activation email
         */
        'activate'         => 'emails.activate',
        /**
         * The subject of the account activation email
         */
        'activate_subject' => 'Your account need activating',
    ],
    'validator'             => [
        /*
         * The validator rules when creating new user
         */
        'create' => [
            'email'                 => 'required|email|unique:users',
            'password'              => 'required',
            'password_confirmation' => 'required|same:password',
        ],
        /*
         * The validator rules when updating a user
         */
        'update' => [
        ],
    ],
];
