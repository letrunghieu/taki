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

    /*
     * Is the `username` field required when registering new account?
     */
    'username.required'            => true,

    /**
     * Validate a username
     */
    'username.validator'           => 'required|min:3|max:50',

    /*
     * Which field is used when authenticating user.
     *
     * Available values: email, username, both
     * Default value: both
     */
    'login_by'                     => 'both',

    /*
     * The name of the `username` field
     */
    'field.username'               => 'username',

    /*
     * The name of the `email` field
     */
    'field.email'                  => 'email',

    /*
     * The name of the key of input array when user want to login by username or email
     */
    'field.both'                   => 'login',

    /*
     * If this config is set to true, when users changed their emails, all
     * links from social accounts will be kept so that they can still log
     * in by these social accounts.
     *
     * Otherwise, all old links will be deleted. When they logging in by
     * these social account, a new registration is created instead.
     */
    'social.email_changed_allowed' => true,

    /*
     * If the password is required, after successfully authenticated from
     * third party social providers, user need to provide password before
     * their account can be created.
     *
     * Otherwise, they can be logged in with the new account without the
     * password. They can provide password later if they want to login
     * with the email.
     */
    'social.password_required'     => true,

    /*
     * How to get the username from the information that social provider
     * returns to us?
     */
    'social.username_callback'     => function ($user)
    {
        return '';
    },

    /*
     * Generate the username automatically from the user information from
     * social provider or force user to enter their username
     */
    'social.username_auto'         => true,

];
