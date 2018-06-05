<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\Jobs\SendWelcomeEmail;

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

    protected $auth;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;

        $this->middleware('guest', ['except' => 'getLogout']);
    }

    public function postLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email', 'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        if ($this->auth->attempt($credentials)) {
            if (Auth::user()->approval === 'denied') {
                Auth::logout();
                session()->flash('authMessage', 'This account has been locked.
                    Please contact <a href="mailto:support@traumaanalytics.com" class="text-danger">support@traumaanalytics.com</a>.');
                return redirect($this->loginPath())
                    ->withInput($request->only('email'));
            }
            return redirect()->intended($this->redirectPath());
        }

        return redirect($this->loginPath())
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => $this->getFailedLoginMessage(),
            ]);
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
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
            'birthday' => 'required|max:25',
            'phone' => 'required|max:50',
            'hospital_name' => 'required|max:255',
            'hospital_level' => 'required|max:50',
            'hospital_address1' => 'required|max:255',
            'hospital_city' => 'required|max:255',
            'hospital_state' => 'required|max:50',
            'hospital_zipcode' => 'required|max:50',
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
        $this->redirectTo = '/profile';

        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'birthday' => $data['birthday'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'unit' => $data['unit'],
            'city' => $data['city'],
            'state' => $data['state'],
            'zipcode' => $data['zipcode'],
            'hospital_name' => $data['hospital_name'],
            'hospital_level' => $data['hospital_level'],
            'hospital_ntdb' => $data['hospital_ntdb'],
            'hospital_tqip' => $data['hospital_tqip'],
            'hospital_address1' => $data['hospital_address1'],
            'hospital_address2' => $data['hospital_address2'],
            'hospital_address3' => $data['hospital_address3'],
            'hospital_city' => $data['hospital_city'],
            'hospital_state' => $data['hospital_state'],
            'hospital_zipcode' => $data['hospital_zipcode'],
            'ssn' => $data['ssn'],
            'credentials' => $data['credentials'],
            'state_license' => $data['state_license'],
        ]);

        $this->dispatch(new SendWelcomeEmail($user));

        return $user;
    }
}
