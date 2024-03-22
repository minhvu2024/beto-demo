<?php

namespace UserFront\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class AuthController extends \Zoe\Http\ControllerFront
{
    use AuthenticatesUsers;
    protected $redirectTo = '/';

    public function username()
    {
        return 'username';
    }

    public function __construct()
    {
        $this->middleware('guest:frontend')->except('logout');
    }

    protected function attemptLogin(Request $request)
    {
        return Auth::guard('frontend')->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

    public function postLogin(Request $request)
    {
        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }
        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }
        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }

    public function getRegister()
    {
        return view('auth.register');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required|max:255|unique:front',
            'password' => 'required|min:6|confirmed',
        ]);
    }
    public function getLogin()
    {
        return redirect(route('backend:dashboard:list'));
        return $this->render('auth.login', [], 'user');
    }
    public function postRegister()
    {
        return back();
    }
    public function postLogout(Request $request)
    {
        $this->guard("frontend")->logout();
        $request->session()->invalidate();
        return redirect('/login');
    }
}