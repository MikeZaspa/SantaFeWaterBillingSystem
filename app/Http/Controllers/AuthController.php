<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeMail;

class AuthController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.admin-register');
    }
    
    public function showLoginForm()
    {
        return view('auth.admin-login');
    }
    
    public function showDashboard()
    {
        return view('auth.admin-dashboard');
    }
    
    public function showConsumerForm()
    {
        return view('auth.admin-consumer');
    }
    
    public function showPLumber()
    {
        return view('auth.admin-plumber');
    }
    
    public function showPlumberConsumerForm()
    {
        return view('auth.admin-plumber-consumer');
    }
    
    public function showPlumberForm()
    {
        return view('auth.admin-plumber-dashboard');
    }
    
    public function showAccountantForm()
    {
        return view('auth.admin-accountant-dashboard');
    }
    
    public function showAccountantConsumerForm()
    {
        return view('auth.admin-accountant-consumer');
    }
    
    public function showRatesForm()
    {
        return view('auth.water-rates');
    }
    
    public function showManageConsumerForm()
    {
        return view('auth.admin-consumer-form');
    }
    
    public function showAccountantreportsForm()
    {
        return view('auth.admin-accountant-reports');
    }
    
    public function showConsumerPortalForm()
    {
        return view('auth.consumer-portal');
    }
    
    public function showHistoryForm()
    {
        return view('auth.consumer-history');
    }
    
    public function showPaymentForm()
    {
        return view('auth.consumer-dashboard');
    }
    
    public function showPaidForm()
    {
        return view('auth.consumer-paid');
    }

     public function showOnlineBillingForm()
    {
        return view('auth.online-billing');
    }
     public function showPaymentVerificationForm()
    {
        return view('auth.paymentVerificationSection');
    }
    public function showAdminAccountant()
    {
        return view('auth.admin-accountant');
    }
     public function showDisconnectionForm()
    {
        return view('auth.admin-plumber-disconnection');
    }

    public function showInformation()
    {
        return view('auth.consumer-information');
    }
    public function showVerifyForm()
    {
        $email = session('verification_email');
        
        if (!$email) {
            return redirect()->route('admin-register')->with('error', 'Please register first.');
        }
        
        return view('auth.verify', compact('email'));
    }
    
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'role' => 'required|in:admin,accountant,plumber',
            'email' => 'required|string|email|max:255|unique:admins',
            'contact_number' => 'required|string|max:20',
            'password' => ['required', 'confirmed', Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Generate verification code
        $verificationCode = rand(100000, 999999);
        
        $admin = Admin::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'birthdate' => $request->birthdate,
            'gender' => $request->gender,
            'role' => $request->role,
            'email' => $request->email,
            'contact_number' => $request->contact_number,
            'password' => Hash::make($request->password),
            'verification_code' => $verificationCode,
            'verification_code_sent_at' => now(),
        ]);

        // Send verification email
        Mail::to($admin->email)->send(new VerificationCodeMail($verificationCode));
        
        // Store email in session for verification
        $request->session()->put('verification_email', $admin->email);

        return redirect()->route('verify')
            ->with('success', 'Registration successful! Please check your email for the verification code.');
    }

     public function verifyCode(Request $request)
    {
        $request->validate([
            'digit1' => 'required|digits:1',
            'digit2' => 'required|digits:1',
            'digit3' => 'required|digits:1',
            'digit4' => 'required|digits:1',
            'digit5' => 'required|digits:1',
            'digit6' => 'required|digits:1',
            'email'  => 'required|email'
        ]);

        $code = (string) (
            $request->digit1 .
            $request->digit2 .
            $request->digit3 .
            $request->digit4 .
            $request->digit5 .
            $request->digit6
        );

        $user = Admin::where('email', $request->email)->first();

        if (!$user) {
            return $this->jsonOrRedirect($request, false, 'User not found.');
        }

        if ((string) $user->verification_code !== $code) {
            return $this->jsonOrRedirect($request, false, 'Invalid verification code.');
        }

        if ($user->verification_code_sent_at->diffInMinutes(now()) > 1) {
            return $this->jsonOrRedirect($request, false, 'Verification code has expired. Please request a new one.');
        }

        // ✅ Mark user as verified
        $user->email_verified_at = now();
        $user->verification_code = null;
        $user->save();

        $request->session()->forget('verification_email');

        return $this->jsonOrRedirect($request, true, 'Email verified successfully. You can now login.', route('admin-login'));
    }

    public function resendCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = Admin::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ], 404);
        }

        // ✅ Generate new code
        $newCode = rand(100000, 999999);
        $user->verification_code = $newCode;
        $user->verification_code_sent_at = now();
        $user->save();

        // Send email
        Mail::to($user->email)->send(new VerificationCodeMail($newCode));

        return response()->json([
            'success' => true,
            'message' => 'A new verification code has been sent to your email.'
        ]);
    }

    /**
     * Helper: Return JSON if AJAX, redirect if normal request
     */
    private function jsonOrRedirect(Request $request, $success, $message, $redirect = null)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => $success,
                'message' => $message,
                'redirect' => $redirect
            ]);
        }

        if ($success) {
            return $redirect
                ? redirect($redirect)->with('success', $message)
                : back()->with('success', $message);
        } else {
            return back()->with('error', $message);
        }
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $credentials = $request->only('email', 'password');

        // Check if user exists first
        $user = Admin::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'The provided email does not exist in our system.',
            ])->onlyInput('email');
        }
        
        // Check if email is verified
        if (!$user->email_verified_at) {
            // Store email in session for verification
            $request->session()->put('verification_email', $user->email);
            
            return redirect()->route('verify')
                ->with('error', 'Please verify your email address before logging in.');
        }

        // Attempt authentication
        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            
            // Check if the user is active (add this if you have an 'active' column)
            if (isset($user->active) && !$user->active) {
                Auth::guard('admin')->logout();
                return back()->withErrors([
                    'email' => 'Your account is inactive. Please contact administrator.',
                ])->onlyInput('email');
            }

            return $this->redirectToDashboard($user->role);
        }

        // If authentication failed (wrong password)
        return back()->withErrors([
            'password' => 'The provided password is incorrect.',
        ])->onlyInput('email');
    }

    protected function redirectToDashboard($role)
    {
        $dashboardRoutes = [
            'admin' => '/admin-dashboard',
            'accountant' => '/admin-accountant-dashboard',
            'plumber' => '/admin-plumber-dashboard',
        ];

        if (!array_key_exists($role, $dashboardRoutes)) {
            Auth::guard('admin')->logout();
            return redirect('/login')->withErrors([
                'role' => 'Invalid user role. Access denied.'
            ]);
        }

        return redirect()->intended($dashboardRoutes[$role]);
    }
}