<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;
use Illuminate\Support\Facades\Hash;

class VerificationController extends Controller
{
    // Show verification form
    public function showVerificationForm(Request $request)
    {
        $email = $request->session()->get('verification_email');
        
        if (!$email) {
            return redirect()->route('admin-register')->with('error', 'Please register first.');
        }
        
        return view('auth.verify', compact('email'));
    }

    // Verify the code
    public function verify(Request $request)
    {
        $request->validate([
            'digit1' => 'required|digits:1',
            'digit2' => 'required|digits:1',
            'digit3' => 'required|digits:1',
            'digit4' => 'required|digits:1',
            'digit5' => 'required|digits:1',
            'digit6' => 'required|digits:1',
            'email' => 'required|email'
        ]);
        
        $code = $request->digit1 . $request->digit2 . $request->digit3 . 
                $request->digit4 . $request->digit5 . $request->digit6;
        
        // Find user by email
        $user = Admin::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->with('error', 'User not found.');
        }
        
        // Check if verification code matches and is not expired (10 minutes)
        if ($user->verification_code !== $code) {
            return back()->with('error', 'Invalid verification code.');
        }
        
        if ($user->verification_code_sent_at->diffInMinutes(now()) > 10) {
            return back()->with('error', 'Verification code has expired. Please request a new one.');
        }
        
        // Mark user as verified
        $user->email_verified_at = now();
        $user->verification_code = null;
        $user->save();
        
        $request->session()->forget('verification_email');
        
        return redirect()->route('admin-login')->with('success', 'Email verified successfully. You can now login.');
    }
    
    // Resend verification code
    public function resend(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);
        
        $user = Admin::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->with('error', 'User not found.');
        }
        
        // Generate new verification code
        $newCode = rand(100000, 999999);
        $user->verification_code = $newCode;
        $user->verification_code_sent_at = now();
        $user->save();
        
        // Send verification email
        Mail::to($user->email)->send(new VerificationCodeMail($newCode));
        
        return back()->with('success', 'A new verification code has been sent to your email.');
    }
}