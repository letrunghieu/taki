# Taki

A collection of useful traits to make Laravel 5.1 authentication system more flexible.

[![Build Status](https://travis-ci.org/letrunghieu/taki.svg?branch=master)](https://travis-ci.org/letrunghieu/taki)
[![Code Climate](https://codeclimate.com/github/letrunghieu/taki/badges/gpa.svg)](https://codeclimate.com/github/letrunghieu/taki)
[![Test Coverage](https://codeclimate.com/github/letrunghieu/taki/badges/coverage.svg)](https://codeclimate.com/github/letrunghieu/taki/coverage)
[![Latest Stable Version](https://poser.pugx.org/hieu-le/taki/v/stable)](https://packagist.org/packages/hieu-le/taki) [![Total Downloads](https://poser.pugx.org/hieu-le/taki/downloads)](https://packagist.org/packages/hieu-le/taki)  [![License](https://poser.pugx.org/hieu-le/taki/license)](https://packagist.org/packages/hieu-le/taki)

## Why?

Laravel 5.1 already has a cute authentication system. However, user can make it more beautiful by some traits in this package:

* Allow user to log in by username, email or both
* Allow user to set their password before creating a new account with Social service providers (facebook, google, ...)
* Allow user to set their username before creating a new account with Social service providers (facebook, google, ...)
* More configurable options for redirecting paths
* Full test suite

## Installation

First, you have to require this package via composer

    composer require "hieu-le/taki"

Now, register the service provider by adding this line into your `providers` array inside the `config/app.php` file:

    HieuLe\Taki\TakiServiceProvider::class,

Register an alias by adding this line into your `aliases` array inside the `config/app.php` file:

    'Taki' => HieuLe\Taki\TakiFacade::class,

If you use traditional RDBMS, this package is shipped with a built in migration file for the `users` table, please remove the Laravel's one at `database/migrations/xxxx_xx_xx_xxxxxx_create_user_table.php` and run this command

    php artisan vendor:publish --provider="HieuLe\Taki\TakiServiceProvider"

Edit the new migration file and run `php artisan migrate` when you're ready.

## Usage

This package helps you by implementing some methods in your authentication controller. This is the list of them, you have to create the others by yourself (most of them is just **GET** method which simply returns a view).

| method | responsibility |
|-------------------|-------------------------------------------------------------------------------------------------|
| `postLogin` | Handle the login request |
| `postRegister` | Handle the registration request |
| `getActivate` | Handle the account activation request |
| `getOauth` | Handle the request when user click the li |
| `getOauthCallback` | Handle the returned data from third-party social network |
| `postOauthComplete` | Handle the registration request after some information is retrieved from OAuth 2 authentication |
| `getLogout` | Handle the logout request |


For password recovery and password reset, the built in trait of Laravel is good enough to me. Therefore, I do not create any modification to it at least in the first release of this package.

Now, use the `HieuLe\Taki\Traits\TakiTrait` in your authentication controller, register routes and implement views.

## Configurations

When running the `vendor:publish` command above, a new file named `taki.php` is created in your `config` folder. If you want to modify any option of this package, edit this file.

| option | description | values | default |
|---------------------------------|-----------------------------------------------------------------------------------------------------------------------------------------------------------|-----------------------------|--------------------------------------------------------------------------------------------------------------------------------|
| `taki.username.required` | Is the `username` field required when registering new account? | boolean | `false` |
| `taki.username.validator` | The validation rule of  the `username` field` | string | `required|min:3|max:50` |
| `taki.login_by` | Which field is used when authenticating user. | `email`, `username`, `both` | `email` |
| `taki.confirm_after_created` | Do users need to confirm their email after creating account? if the emails do not need to verified, users will be logged in right after being registered. | boolean | `false` |
| `taki.field.username` | The name of username field | string | `username` |
| `taki.field.email` | The name of email field | string | `email` |
| `taki.field.both` | The name of the input field when accepting both username and email in log in process | string | `login` |
| `taki.social.password_required` | Do users need to provide password before creating account with social network credentials? | boolean | `false` |
| `taki.social.username_required` | Do users need to provide username before creating account with social network credentials? | boolean | `false` |
| `taki.emails.activate` | The view name of account activation email. If its value is an array, the first element is the HTML view name, the second element is the text view name. | array|string | `emails.activate` |
| `taki.emails.activate_subject` | The subject of the account activation email | string | `Your account need activating` |
| `taki.validator.create` | The validator rules when creating new user | array | view in file |

## Controller properties and methods

### Properties

Taki looks at some properties in your controller and allows you to customize its behavior via these properties:

| property | responsibility | default  |
|------------------------|------------------------------------------------------------------------------------------------------------------------------------------------------------------|-------------------|
| `loginPath` | The path user is redirected to when login failed | `/auth/login` |
| `redirectAfterLogout` | The path user is redirected to after being logged out | `/` |
| `postRegisterRedirect` | The path user is redirected to after a success registration | `/home` |
| `oauthCompletedPath` | The path user is redirected to after authenticated in social service, this path will display another form to allow user to supply their username and/or password | `/oauth/complete` |
| `activatedView` | The view name of user activation page | `auth.activate`

### Methods

Implementing this methods allows you to gain more control over your authentication flow:

| method | executed at |
|-----------------------------------|----------------------------------------------------------------------|
| `authenticated($request, $user)` | After user is logged in and before the redirect response is returned |
| `userRegistered($request, $user)` | After user's account is created |
| `userActivated` | After user's account is activated |

## License

This package is release under the [MIT license](LICENSE), feel free to use it in your work.
