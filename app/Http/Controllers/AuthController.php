<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function verifyPin(Request $request)
    {
        $request->validate([
            'pin' => 'required|digits:4',
            'username' => 'required'
        ]);

        $user = User::where('username', $request->username)->first();

        if ($user && Hash::check($request->pin, $user->pin)) {
            Auth::login($user);

            return response()->json([
                'success' => true,
                'message' => 'PIN Benar. Mengalihkan...'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'PIN salah, coba lagi'
        ], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'pin' => 'required|digits:4',
            'pin_confirmation' => 'required|same:pin'
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'pin' => Hash::make($request->pin),
        ]);

        Auth::login($user);

        return redirect()->route('app.index');
    }
}
