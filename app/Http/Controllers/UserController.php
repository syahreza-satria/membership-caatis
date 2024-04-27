<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Show Register Form
    public function create(){
        return view('users.register');
    }

    // Create New User
    public function store(Request $request){
        $formFields = $request->validate([
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'phone_number' => ['required', 'min:9', 'max:14', 'string', Rule::unique('users')],
            'password' => ['required', 'confirmed', 'min:6']
        ]);

        // Hash Password
        $formFields['password'] = bcrypt($formFields['password']);

        // Create User
        $user = User::create($formFields);

        // Login
        auth()->login($user);

        return redirect('/')->with('message', 'User Created and logged in');
    }

    // Logout User
    public function logout(Request $request){
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('message', 'You Have Been logout');
    }

    // Show Login Form
    public function login(){
        return view('users.login');
    }

    // Authenticate user
    public function authenticate(Request $request)
    {
        // dd($request);
        $request->validate([
            'phone_number' => 'required',
            'password' => 'required',
        ]);
        
        // Custom credentials using 'phone_number' instead of 'email'
        $credentials = [
            'phone_number' => $request->input('phone_number'),
            'password' => $request->input('password'),
        ];
        // dd($credentials);
        if (auth()->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()->withErrors(['phone_number' => 'Invalid Credentials'])->onlyInput('phone_number');
    }
}
