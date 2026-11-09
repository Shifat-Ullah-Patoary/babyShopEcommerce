<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $user = User::create($data);
        Auth::login($user);

        return to_route('home')->with('success', 'Welcome to Little Nest!');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate(['email' => ['required', 'email'], 'password' => ['required', 'string']]);
        if (
            hash_equals((string) env('ADMIN_EMAIL', 'test@gmail.com'), (string) $credentials['email'])
            && hash_equals((string) env('ADMIN_PASSWORD', '12345678'), (string) $credentials['password'])
        ) {
            Auth::logout();
            $request->session()->regenerate();
            $request->session()->put('is_admin', true);

            return to_route('admin.dashboard')->with('success', 'Welcome to the dashboard!');
        }

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Those login details are not correct.'])->onlyInput('email');
        }
        $request->session()->regenerate();
        $request->session()->forget('is_admin');

        return to_route('home')->with('success', 'Welcome back!');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->forget('is_admin');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('home');
    }
}
