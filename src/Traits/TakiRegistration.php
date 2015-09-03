<?php

namespace HieuLe\Taki\Traits;

use App\User;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

/**
 * Taki registration trait.
 *
 * Use this trait to replace the built-in RegisterUsers trait
 *
 * @package HieuLe\Taki
 */
trait TakiRegistration
{

    use RedirectsUsers;

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function postRegister(Request $request)
    {
        $this->validate($request, $this->validateCreating());

        $user = $this->create($request->all());
        if (config('taki.confirm_after_created')) {
            $token              = app('auth.password.tokens')->createNewToken();
            $user->is_activated = 0;
            $user->token        = $token;
            $user->save();
            Mail::queue(config('taki.emails.activate'), compact('token', 'user'), function ($m) use ($user, $token) {
                $m->subject(config('taki.emails.activate_subject'));
                $m->to($user->getEmailForPasswordReset());
            });
        } else {
            Auth::login($user);
        }

        if (method_exists($this, 'userRegistered')) {
            $this->userRegistered($request, $user);
        }


        return redirect($this->getPostRegisterRedirectPath());
    }

    /**
     * Activate a user by a link
     *
     * @param $token
     *
     * @return \Illuminate\View\View
     * @throws NotFoundHttpException
     */
    public function getActivate(Request $request, $token)
    {

        $user = User::where('token', $token)->first();
        if (!$user) {
            throw new NotFoundHttpException;
        }

        $user->is_activated = true;
        $user->token        = null;
        $user->save();

        if (method_exists($this, 'userActivated')) {
            $this->userRegistered($request, $token, $user);
        }

        return view($this->getUserActivatingView(), ['user' => $user, 'token' => $token]);
    }

    /**
     * Get the redirect path after registered successfully
     *
     * @return string
     */
    protected function getPostRegisterRedirectPath()
    {
        if (property_exists($this, 'postRegisterRedirect')) {
            return $this->postRegisterRedirect;
        }

        return $this->redirectPath();
    }

    /**
     * get the validation rules when creating user
     *
     * @param array $data
     *
     * @return array
     */
    protected function validateCreating()
    {
        $rules = config('taki.validator.create', []);

        if (config('taki.username.required') && !array_get($rules, config('taki.field.username'))) {
            $rules[config('taki.field.username')] = config('taki.username.validator', 'required');
        }

        return $rules;
    }

    protected function getUserActivatingView()
    {
        if (property_exists($this, 'activatedView')) {
            return $this->activatedView;
        } else {
            return 'auth.activate';
        }
    }
}
