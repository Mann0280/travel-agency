<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Auth;

class ForgotPasswordController extends Controller
{
    // Show form to request link (not used if handled via Login JS)
    public function showLinkRequestForm()
    {
        return view('user.auth.passwords.email');
    }

    // Send reset link
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $email = $request->email;
        $user = User::where('email', $email)->first();

        // Rate limit check (e.g., 1 per minute)
        $recent = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('created_at', '>', Carbon::now()->subMinute())
            ->first();

        if ($recent) {
            return back()->with('status', 'Please wait before requesting another link.');
        }

        // Generic success message (Security: don't expose if email exists)
        $genericMessage = 'If this email is registered, you will receive a reset link shortly.';

        if (!$user) {
            $this->logResetRequest($email, 'failed');
            return back()->with('status', $genericMessage);
        }

        // Generate secure token
        $token = Str::random(64);
        
        // Store hashed token (Laravel default uses email as primary)
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => Hash::make($token),
                'created_at' => Carbon::now()
            ]
        );

        // Log the request
        $this->logResetRequest($email, 'requested');

        // Send Email
        try {
            Mail::to($email)->send(new ResetPasswordMail($token, $email));
        } catch (\Exception $e) {
            // Log the error for the developer
            \Illuminate\Support\Facades\Log::error('Mail sending failed: ' . $e->getMessage());
            
            // Log as failed in our custom logs
            $this->logResetRequest($email, 'failed');
            
            return back()->with('status', 'We encountered an error sending the email. Please try again later or contact support.');
        }

        return back()->with('status', $genericMessage);
    }

    // Show reset form
    public function showResetForm(Request $request, $token = null)
    {
        return view('user.auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    // Handle Password Reset
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required', 'confirmed', 'min:8',
                'regex:/[A-Z]/', // At least one uppercase
                'regex:/[0-9]/', // At least one number
            ],
        ], [
            'password.regex' => 'The password must contain at least one uppercase letter and one number.'
        ]);

        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$record || !Hash::check($request->token, $record->token)) {
            $this->logResetRequest($request->email, 'failed');
            return back()->withErrors(['email' => 'Invalid or expired token.']);
        }

        // Check expiry (60 minutes)
        if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            $this->logResetRequest($request->email, 'expired');
            return back()->withErrors(['email' => 'This reset link has expired.']);
        }

        // Update Password
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();

            // Invalidate/Delete token
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            // Log Success
            $this->logResetRequest($request->email, 'success');

            // Force logout from all sessions
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('success', 'Password reset successfully! Please login with your new password.');
        }

        return back()->withErrors(['email' => 'User not found.']);
    }

    protected function logResetRequest($email, $status)
    {
        DB::table('password_reset_logs')->insert([
            'email' => $email,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'status' => $status,
            'created_at' => Carbon::now(),
        ]);
    }
}
