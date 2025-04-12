<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Config;
use DB;


class AuthController extends Controller
{
    public function RegisterStore()
    {
        if(Auth::check()){
            redirect('dashboard');
        }
        
        return view('auth.register');
    }

    public function RegisterCreate(Request $request){
        $validator = Validator::make($request->all(), [
           'name' => 'required',
           'last_name' => 'required',
           'user_type' => 'required',
           'email' => 'required|email|unique:users,email',
           'mobile' => 'required|digits:10',
           'password' => 'required',
       ]);
        if($validator->passes()){
          $userRegister = new User();
          $userRegister->name = isset($request->name) ? $request->name : '';
          $userRegister->last_name = isset($request->last_name) ? $request->last_name : '';
          $userRegister->email = isset($request->email) ? $request->email : '';
          $userRegister->mobile = isset($request->mobile) ? $request->mobile : '';
          $userRegister->user_type = isset($request->user_type) ? $request->user_type : '';
          $userRegister->password = Hash::make($request->password);
          $userRegister->save();
          return response()->json([
           'status' => true,
           'message' => "User register succssfully",
       ]);
   }else{
      return response()->json([
          'status' => false,
          'errors' => $validator->errors(),
       ]);
   
     }
   
   }


    public function loginForm()
    {
        if(Auth::check()){
            redirect('dashboard');
        }
        return view('auth.login');
    }

    public function authuser(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        $email = $request->email;
        $password = $request->password;

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Auth::attempt(['email' => $email, 'password' => $password]);
        } 

        if (Auth::check()) {
            $user = Auth::user();
            
            if ($user->user_type == 'admin') {
                session(['user_type' => $user->user_type]);
                return redirect()->intended(route('admin.dashboard'))->withSuccess('Signed in');
            } else if ($user->user_type == 'user') {
                return redirect(route('admin.dashboard'))->withSuccess('Signed in');
            } else {
                Auth::logout();
                return back()->withErrors(['error' => 'Your account is not yet active.']);
            }
        }
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function dashboard(Request $request)
    {
        $user = Auth::user();
        if($user->user_type == "admin"){
            return view('admin.dashboard.dashboard');
        }
        if($user->user_type == "user"){
            return view('admin.dashboard.dashboard');
        }
      }

      public function logoutUser(Request $request) {
        Auth::logout();
        Session::flush();
        return redirect()->route('user-login');
    }
}
