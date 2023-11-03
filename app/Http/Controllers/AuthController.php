<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    //

    public function attempt($credentials): bool
    {
        $email_username = $credentials["email_username"];
        $password = $credentials["password"];

        $user = User::where('email', $email_username)
            // ->orWhere('username', $email_username)
            ->first();
        if (!$user) {
            return false;
        }
        $check_password = Hash::check($password, $user->password);
        if (!$check_password) {
            return false;
        }
        Auth::login($user);
        return true;
    }
    public function login(AuthRequest $request)
    {
        $credentials = $request->only("email_username", "password");
        if ($this->attempt($credentials)) {
            $request->session()->regenerate();
            return back();
        } else {
            return back()->withErrors([
                'login' => 'Sai tên đăng nhập hoặc mật khẩu.',
            ])->withInput(['email_username']);
        }

    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->regenerate();
        return redirect()->route('guest.login_page');
    }
}