<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Exception;

class ForgotPasswordController extends Controller
{
    // Show Forgot Password Form
    public function showForgetPasswordForm()
    {
        try {
            return view('authentication.forgetPassword');
        } catch (Exception $e) {
            Log::error('Show Forget Password Form Error: ' . $e->getMessage());
            return abort(500);
        }
    }

    // Send Reset Password Email
    public function submitForgetPasswordForm(Request $request)
    {
        try {
            $request->validate(['email' => 'required|email']);

            $status = Password::sendResetLink(
                $request->only('email')
            );

            return $status === Password::ResetLinkSent
                ? back()->with(['status' => __($status)])
                : back()->withErrors(['email' => __($status)]);
        } catch (Exception $e) {
            Log::error('Submit Forget Password Form Error: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong while sending the reset email.');
        }
    }

    // Show Reset Password Form
    public function showResetPasswordForm($token)
    {
        try {
            return view('authentication.forgetPasswordLink', [
                'token' => $token,
            ]);
        } catch (Exception $e) {
            Log::error('Show Reset Password Form Error: ' . $e->getMessage());
            return abort(500);
        }
    }

    // Reset Password
    public function submitResetPasswordForm(Request $request)
    {
        try {
            $request->validate([
                'token' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:8|confirmed',
            ]);

            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function (User $user, string $password) {
                    $user->forceFill([
                        'password' => $password,
                    ])->setRememberToken(Str::random(60));

                    $user->save();

                    event(new PasswordReset($user));
                }
            );

            return $status === Password::PasswordReset
                ? redirect()->route('login')->with('status', __($status))
                : back()->withErrors(['email' => [__($status)]]);
        } catch (Exception $e) {
            Log::error('Submit Reset Password Form Error: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong while resetting your password.');
        }
    }
}
