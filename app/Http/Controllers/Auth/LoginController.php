<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;

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
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Override default Laravel login
     *
     * @return void
     */
    protected function login(Request $request)
    {
        $input = $request->all();
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);

        $fieldType = filter_var($request->username, FILTER_FLAG_STRIP_LOW ) ? 'username' : null;
        if(auth()->attempt(array($fieldType => $input['username'], 'password' => $input['password']))) {
            switch(\Auth::user()->role->name) {
                case 'superadmin': 
                    return redirect('/superadmin');
                break;
                case 'admin': 
                    return redirect('/admin');
                break;
                case 'hospital_admin': 
                    return redirect('/user');
                break;
                case 'observer': 
                    return redirect('/observer');
                break;
                default: return redirect()->route('login')->with('error','Role not initialized.');
            }
        } 
        else {
            return redirect()->route('login')
            ->with('error','Email-Address And Password Are Wrong.');
        }
    }

    // protected function authenticated(Request $request, $user)
    // {
    //     if($user->role('superadministrator')){
    //         return redirect('/admin');
    //     }
        
    //     if($user->role('user')){
    //         return redirect('/user');
    //     }
    // }

}
