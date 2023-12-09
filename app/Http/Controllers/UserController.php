<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function create()
    {
        return view('users.create');
    }

    public function store()
    {
        // * Validate the request...
        $formFields = request()->validate([
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => 'required|min:6|confirmed'
        ]);

        // * Hash the password
        $formFields['password'] = bcrypt($formFields['password']);

        // * Create user
        $user = User::create($formFields);

        // * Sign in the user
        auth()->login($user);

        // * Redirect to home page
        return redirect('/')->with('success', 'Account created successfully!');
    }

    public function login()
    {
        return view('users.login');
    }

    public function authenticate()
    {
        // * Validate the request...
        $formFields = request()->validate([
            'email' => ['required', 'email'],
            'password' => 'required'
        ]);

        // * Attempt to authenticate the user
        if (!auth()->attempt($formFields)) {
            return back()->withErrors([
                'email' => 'Invalid credentials'
            ])->onlyInput('email');
        }

        request()->session()->regenerateToken();

        // * Redirect to home page
        return redirect('/')->with('success', 'You are now logged in!');
    }

    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'You are now logged out!');
    }
}
