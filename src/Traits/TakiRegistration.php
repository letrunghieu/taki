<?php

namespace HieuLe\Taki;

use App\User;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Support\Facades\Auth;

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
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function postRegister(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails())
        {
            $this->throwValidationException(
                $request, $validator
            );
        }

        $user = $this->create($request->all());
        if (config('taki.confirm_after_created'))
        {
            $token = app('auth.password.tokens')->createNewToken();
            $user->is_activated = 0;
            $user->token = $token;
            $user->save();
            $this->mailer->send(config('taki.emails.password_reset'), compact('token', 'user'), function ($m) use ($user, $token)
            {
                $m->to($user->getEmailForPasswordReset());
            });
        } else
        {
            Auth::login($user);
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
    public function getActivate($token)
    {

        $user = User::where('token', $token)->first();
        if (!$user)
        {
            throw new NotFoundHttpException;
        }

        return view('auth.activate', ['user' => $user]);
    }

    /**
     * Get the redirect path after registered successfully
     *
     * @return string
     */
    protected function getPostRegisterRedirectPath()
    {
        if (property_exists($this, 'postRegisterRedirect'))
        {
            return $this->postRegisterRedirect;
        }

        return $this->redirectPath();
    }
}