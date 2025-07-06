<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $this->validateRegistration($request);

        $user = $this->createUser($request);

        Auth::login($user);

        return redirect('/todos')->with('success', 'Registration successful!');
    }
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $this->validateLogin($request);

        if ($this->attemptLogin($credentials, $request)) {
            return redirect()->intended('/todos')->with('success', 'Login successful!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Logged out successfully!');
    }
    /**
     * Validate the registration request data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    private function validateRegistration(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
    }

    /**
     * Create a new user instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Models\User
     */
    private function createUser(Request $request): User
    {
        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    }

    /**
     * Validate the login request data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    private function validateLogin(Request $request): array
    {
        return $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    }

    /**
     * Attempt to log the user in.
     *
     * @param  array  $credentials
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    private function attemptLogin(array $credentials, Request $request): bool
    {
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return true;
        }
        return false;
    }
}
