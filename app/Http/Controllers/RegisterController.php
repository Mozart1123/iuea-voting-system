<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'string', 
                'lowercase', 
                'email', 
                'max:255', 
                'unique:'.User::class,
                'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/i'
            ],
            'student_id' => ['required', 'string', 'max:50', 'unique:'.User::class],
            'faculty' => ['required', 'string', 'max:255'],
            'password' => [
                'required', 
                'confirmed', 
                \Illuminate\Validation\Rules\Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
        ], [
            'email.regex' => 'You must use a valid @gmail.com email address.',
        ]);

        $photoPath = null;
        if ($request->hasFile('profile_photo')) {
            $photoPath = $request->file('profile_photo')->store('profiles', 'public');
        }

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'student_id' => $request->student_id,
            'faculty' => $request->faculty,
            'password' => Hash::make($request->password),
            'role' => 'student',
            'profile_photo' => $photoPath,
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        // Send OTP
        $user->notify(new \App\Notifications\OtpNotification($otp));

        // Store user ID in session for verification
        session(['otp_user_id' => $user->id]);

        return redirect()->route('otp.verify');
    }
}
