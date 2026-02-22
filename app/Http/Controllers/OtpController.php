<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\OtpNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtpController extends Controller
{
    public function show()
    {
        if (!session('otp_user_id')) {
            return redirect()->route('register');
        }
        return view('auth.verify-otp');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $userId = session('otp_user_id');
        $user = User::findOrFail($userId);

        if ($user->otp_code === $request->otp && $user->otp_expires_at->isFuture()) {
            // Clear OTP
            $user->otp_code = null;
            $user->otp_expires_at = null;
            $user->email_verified_at = now();
            $user->save();

            Auth::login($user);
            session()->forget('otp_user_id');

            return redirect()->route('dashboard.index');
        }

        return back()->withErrors(['otp' => 'Le code OTP est invalide ou a expiré.']);
    }

    public function resend(Request $request)
    {
        $userId = session('otp_user_id');
        if (!$userId) {
            return redirect()->route('register');
        }

        $user = User::findOrFail($userId);
        
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->otp_code = $otp;
        $user->otp_expires_at = now()->addMinutes(10);
        $user->save();

        $user->notify(new OtpNotification($otp));

        return back()->with('status', 'Un nouveau code OTP a été envoyé à votre adresse email.');
    }
}
