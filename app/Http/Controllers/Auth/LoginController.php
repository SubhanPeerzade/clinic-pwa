<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();

        $role = Auth::user()->role;

        // Super Admin
        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Doctor → Admin + Doctor + Reception
        if ($role === 'doctor') {
            return redirect()->route('admin.dashboard');
        }

        // Receptionist
        if ($role === 'receptionist') {
            return redirect()->route('reception.dashboard');
        }

        return redirect('/');
    }

    return back()->withErrors([
        'email' => 'Invalid credentials.',
    ])->onlyInput('email');
}





    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
