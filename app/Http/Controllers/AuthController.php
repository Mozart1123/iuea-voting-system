<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Handle an authentication attempt.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required'],
        ]);

        // Check if login is email or student_id
        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'student_id';

        $authAttempt = Auth::attempt([
            $loginType => $request->login,
            'password' => $request->password,
        ]);

        if ($authAttempt) {
            $user = Auth::user();

            // Guard against unverified student accounts
            if ($user->role === 'student' && !$user->email_verified_at) {
                $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                $user->otp_code = $otp;
                $user->otp_expires_at = now()->addMinutes(10);
                $user->save();

                $user->notify(new \App\Notifications\OtpNotification($otp));
                
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                session(['otp_user_id' => $user->id]);
                return redirect()->route('otp.verify')->with('status', 'Veuillez vérifier votre compte avec le code OTP envoyé.');
            }

            $request->session()->regenerate();

            // Redirect based on role
            if ($user->role === 'super_admin') {
                return redirect()->intended(route('admin.super-admin.index'));
            }

            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.index'));
            }

            return redirect()->intended(route('dashboard.index'));
        }

        return back()->withErrors([
            'login' => 'The provided credentials do not match our records.',
        ])->onlyInput('login');
    }

    /**
     * Handle forgot password request.
     */
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // In a real app, you would send the email here.
        // For this demo, we'll just redirect back with a success message.
        return back()->with('status', 'We have emailed your password reset link!');
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
