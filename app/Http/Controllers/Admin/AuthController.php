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
        $this->middleware('guest', ['except' => 'getLogout']);
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
        $credentials = $request->only('email', 'password');
        if (\Auth::attempt($credentials)) {
          // login the user
          session()->save();
          return redirect('/')->with('success','تم الدخول بنجاح');
        }
        // failed
       return redirect()->back()->with('error' , trans('admin_global.msg_error_login'));
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
