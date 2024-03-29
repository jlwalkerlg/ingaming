<?php

namespace App\Controllers;

use Bellona\Http\Controller;

use Bellona\Support\Facades\Session;
use Bellona\Support\Facades\Auth;
use Bellona\Http\Request;
use App\Models\User;
use Bellona\Support\Facades\DB;

class Users extends Controller
{
    public function showLogin()
    {
        $data['title'] = 'Login';
        $data['scripts'] = ['login'];

        render('users/login', $data);
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->data('email'))->first();

        if ($user) {
            if (password_verify($request->data('password'), $user->password)) {
                Auth::login($user);
                Session::flash('alert', 'Logged in.');
                redirect('/');
            }
        }

        Session::flash('alert', 'Problem with login.');
        back();
    }

    public function showRegister()
    {
        $data['title'] = 'Register';
        $data['scripts'] = ['register'];

        render('users/register', $data);
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50',
            'email' => 'required|unique:users|format:email|max:255',
            'password' => 'required|max:255',
            'confirm_password' => 'required|matches:password'
        ]);

        $user = new User;
        $user->assign($request->data());

        $user->password = password_hash($user->password, PASSWORD_DEFAULT);

        try {
            DB::beginTransaction();
            if ($user->save()) {
                $user->createCart();
            }
            DB::commit();
            $success = true;
        } catch (\Throwable $e) {
            DB::rollback();
            $success = false;
        }

        if ($success) {
            Auth::login($user);
            Session::flash('alert', 'Welcome to inGAMING');
            redirect('/');
        }

        $request->save();
        Session::flash('alert', 'Problem creating new user.');
        redirect('register');
    }

    public function logout()
    {
        Auth::logout();
        Session::flash('alert', 'Logged out.');
        redirect('/');
    }
}
