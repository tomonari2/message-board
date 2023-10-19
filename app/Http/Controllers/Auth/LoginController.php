<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/posts';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectToGoogle()
    {
        // すでにユーザーがログイン済みの場合
        if (Auth::check()) {
            return redirect()->route('posts.index');
        }

        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $user = Socialite::driver('google')->user();

        $user = User::firstOrCreate(['sub' =>  $user->user['sub'], 'name' => $user->name]);

        Auth::login($user);
        return redirect()->route('posts.index');
    }

    public function redirectToGitHub()
    {
        // すでにユーザーがログイン済みの場合
        if (Auth::check()) {
            return redirect()->route('posts.index');
        }

        return Socialite::driver('github')->redirect();
    }

    public function handleGitHubCallback()
    {
        $user = Socialite::driver('github')->user();
        dd($user);

        $user = User::firstOrCreate(['sub' =>  $user->user['sub'], 'name' => $user->name]);

        Auth::login($user);
        return redirect()->route('posts.index');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('top'); // ログアウト後にリダイレクトするページを指定します
    }
}
