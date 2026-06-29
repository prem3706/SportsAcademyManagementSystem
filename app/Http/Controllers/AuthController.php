<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    // public function register(Request $request)
    // {
    //     try {
    //         $validatedData = $request->validate([
    //             'firstname' => 'required|string|max:255',
    //             'lastname' => 'required|string|max:255',
    //             'email' => 'required|email|unique:users,email',
    //             'phone' => 'required|string|max:10|unique:users,phone',
    //             'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    //             'password' => 'required|string|min:8',
    //             'gender' => 'required|in:male,female,other',
    //         ]);

    //         // Upload profile picture
    //         if ($request->hasFile('profile_picture')) {
    //             $validatedData['profile_picture'] = $request
    //                 ->file('profile_picture')
    //                 ->store('profile_pictures', 'public');
    //         }

    //         // Default values
    //         $validatedData['role'] = 'player';
    //         $validatedData['status'] = 'active';
    //         $validatedData['joined_at'] = now();

    //         // Password will be automatically hashed
    //         // if you have: 'password' => 'hashed' in User model

    //         $user = User::create($validatedData);

    //         Auth::login($user);

    //         return redirect('/')
    //             ->with('success', 'Registration successful!');
    //     } catch (\Exception $e) {

    //         Log::error('Registration Error: '.$e->getMessage());

    //         return back()
    //             ->withInput()
    //             ->with('error', 'Something went wrong during registration.');
    //     }
    // }

    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if (Auth::attempt($credentials, $request->boolean('remember'))) {

                $request->session()->regenerate();

                $user = Auth::user();

                if ($user->roles()->exists()) {

                    return redirect()
                        ->intended('/')
                        ->with('success', 'Welcome back!');
                }

                Auth::logout();

                return back()
                    ->withErrors([
                        'email' => 'No role assigned to this account.',
                    ]);
            }

            return back()
                ->withErrors([
                    'email' => 'The provided credentials do not match our records.',
                ])
                ->onlyInput('email');

        } catch (\Exception $e) {

            Log::error('Login Error: '.$e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Something went wrong during login.');
        }
    }

    public function logout(Request $request)
    {
        try {
            Auth::logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect('/')
                ->with('success', 'You have been logged out successfully.');

        } catch (\Exception $e) {

            Log::error('Logout Error: '.$e->getMessage());

            return back()
                ->with('error', 'Something went wrong during logout.');
        }
    }
}
