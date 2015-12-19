<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use Socialite;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    private $redirectTo = '/home';
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }


    public function redirectToProvider(){
       return Socialite::driver('facebook')->redirect();
    }
     
    public function handleProviderCallback(){
        $user = Socialite::driver('facebook')->user();
        $userSudahAda = \App\User::Where('idsocial', $user->getId())-> first();
        if ($userSudahAda){
            \Auth::login($userSudahAda);
        }else{
            $newUser = new \App\User();
            $newUser->idsocial = $user->getId();
            $newUser->name = $user->getName();
            $newUser->profile_picture = $user->getAvatar();
            $newUser->email = $user->getEmail();
            $newUser->save();
            \Auth::login($newUser);
        }
            return redirect('home');
    }

    public function authenticated(Request $request, \App\User $user){
        //bikin check tanggal subscribtion
        return redirect()->intended($this->redirectPath());
    }

}
