<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    // Show Register Form
    public function create(){
        return view('users.register');
    }

    // Create New User
    public function store(Request $request){
        $formFields = $request->validate([
            'fullname' => ['required', 'min:3'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'phone' => ['required', 'min:9', 'max:14', 'string', Rule::unique('users')],
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
            'phone' => 'required',
            'password' => 'required',
        ]);
        
        // Custom credentials using 'phone' instead of 'email'
        $credentials = [
            'phone' => $request->input('phone'),
            'password' => $request->input('password'),
        ];
        // dd($credentials);
        if (auth()->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()->withErrors(['phone' => 'Invalid Credentials'])->onlyInput('phone');
    }

    public function showSsoLoginForm()
    {
        return view('users.loginSso');
    }

    public function authenticateSso(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Authenticate with the external API
        $response = Http::post('https://api-gateway.telkomuniversity.ac.id/issueauth', [
            'username' => $request->username,
            'password' => $request->password,
        ]);

        if ($response->failed()) {
            return back()->withErrors(['username' => 'Login failed. Please check your credentials and try again.']);
        }

        // Extract the token from the response
        $token = $response->json()['token'];

        // Get the user profile from the external API using the token
        $profileResponse = Http::withToken($token)->get('https://api-gateway.telkomuniversity.ac.id/issueprofile');

        if ($profileResponse->failed()) {
            return back()->withErrors(['username' => 'Failed to retrieve user profile.']);
        }

        $profile = $profileResponse->json();

        // Check if the user already exists
        $user = User::where('email', $profile['email'])->first();

        if (!$user) {
            // Create a new user if it doesn't exist
            $user = new User();
            $user->fullname = $profile['fullname'];
            $user->email = $profile['email'];
            $user->password = Hash::make($request->password); // Hash the password for security
            $user->phone = $profile['phone'];
            $user->user_points = 700; // default points

            $user->save();
        }

        // Log the user in
        Auth::login($user);

        return redirect()->intended('/');
    }
}
