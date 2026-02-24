<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Google authentication failed.');
        }

        // Check if user exists by google_id or email
        $user = User::where('google_id', $googleUser->id)
                    ->orWhere('email', $googleUser->email)
                    ->first();

        if ($user) {
            // Update user if needed
            $user->update([
                'google_id' => $googleUser->id,
                'profile_photo' => $user->profile_photo ?? $googleUser->avatar,
            ]);
        } else {
            // For new users, we need to handle the faculty and student_id
            // Since this is a direct login, we might want to redirect them to a page to complete their profile
            // OR we create a temporary record. 
            // Better: If user doesn't exist, we create them but they'll need to fill in faculty/student_id
            // For now, let's create them with placeholders and redirect to a "complete profile" if needed.
            // But usually, students are already in the DB or we show an error if they are not IUEA students.
            
            $user = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'password' => Hash::make(Str::random(24)),
                'profile_photo' => $googleUser->avatar,
                'role' => 'student',
                'email_verified_at' => now(),
            ]);
        }

        Auth::login($user);

        // If faculty is missing, redirect to a settings page or show a toast
        if (!$user->faculty || !$user->student_id) {
            return redirect()->route('dashboard.index')->with('warning', 'Please update your student ID and Faculty in settings.');
        }

        return redirect()->route('dashboard.index');
    }
}
