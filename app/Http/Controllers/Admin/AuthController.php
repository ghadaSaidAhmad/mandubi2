<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
class AuthController extends Controller
{
    public function __construct()
    {
       // $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
    * render the login page.
    *
    * @return string|view
    */

    public function getIndex()
    { 
        return view('admin.auth.login');
    }
    
    public function postLogin(LoginRequest $request)
    {
     //   dd( Auth::user()->type);
        $credentials = $request->only('email', 'password');
      //  dd(auth('web')->attempt($credentials));
        if (auth('web')->attempt($credentials)) {
           // dd('true');
            return redirect()->intended('/users');
        }
        dd('sdsd');
        // failed
       return redirect()->back()->with('error' ,'حدث خطأ!');
    }

    /**
    * Logout The user
    */
    public function getLogout()
    {
        
        \Auth::logout();

        return redirect('/')->with('info',trans('admin_global.msg_success_logout'));
    }
}
