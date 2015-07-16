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
        'required'  => true,

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
    'login_by'              => 'both',

    /*
     * Does user need to confirm their email after creating account, if the
     * email do not need to verified, they will be logged in right after
     * registered.
     */
    'confirm_after_created' => true,

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
         * If this config is set to true, when users changed their emails, all
         * links from social accounts will be kept so that they can still log
         * in by these social accounts.
         *
         * Otherwise, all old links will be deleted. When they logging in by
         * these social account, a new registration is created instead.
         */
        'email_changed_allowed' => true,

        /*
         * If the password is required, after successfully authenticated from
         * third party social providers, user need to provide password before
         * their account can be created.
         *
         * Otherwise, they can be logged in with the new account without the
         * password. They can provide password later if they want to login
         * with the email.
         */
        'password_required'     => true,

        /*
         * How to get the username from the information that social provider
         * returns to us?
         */
        'username_callback'     => function ($user)
        {
            return '';
        },

        /*
         * Generate the username automatically from the user information from
         * social provider or force user to enter their username
         */
        'username_auto'         => true,
    ],

    'emails'                => [
        /*
         * The view name of password reset email
         */
        'password_reset' => 'emails.password_reset',
    ],

];