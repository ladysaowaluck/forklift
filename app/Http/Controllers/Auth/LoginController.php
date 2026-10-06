<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/transactions';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     *Override sendFailedLoginResponse to distinguish
     * "user not found" vs "wrong password" and flash a specific
     * session key so the login view can show a targeted popup.
     *
     * @param  \Illuminate\Http\Request  $request
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        $email = $request->input($this->username());

        //[DEBUG] Log every failed login attempt with timestamp
        Log::debug('[LoginController] sendFailedLoginResponse triggered', [
            'email'      => $email,
            'ip'         => $request->ip(),
            'timestamp'  => now()->toDateTimeString(),
        ]);

        //Query DB to check if this email exists at all
        $userExists = User::where('email', $email)->exists();

        Log::debug('[LoginController] User existence check result', [
            'email'      => $email,
            'userExists' => $userExists,
        ]);

        if (! $userExists) {
            //Email not in DB → "User Not Found" popup
            $errorType    = 'user_not_found';
            $errorMessage = 'No account found with that email address.';

            //[DEBUG] Log::info works in Controller — $this->info() is Artisan-only and would crash here
            Log::info('[DEBUG] LoginController → user_not_found for email: ' . $email);
            Log::debug('[LoginController] Result → user_not_found', ['email' => $email]);
        } else {
            //Email found but password wrong → "Incorrect Password" popup
            $errorType    = 'wrong_password';
            $errorMessage = 'Incorrect password. Please try again.';

            //[DEBUG] Same reason — use Log::info, not $this->info()
            Log::info('[DEBUG] LoginController → wrong_password for email: ' . $email);
            Log::debug('[LoginController] Result → wrong_password', ['email' => $email]);
        }

        //Flash both keys so the Blade modal can read them on the redirected GET
        $request->session()->flash('login_error_type',    $errorType);
        $request->session()->flash('login_error_message', $errorMessage);

        Log::debug('[LoginController] Session flashed', [
            'login_error_type'    => $errorType,
            'login_error_message' => $errorMessage,
        ]);

       $failedField = ! $userExists ? $this->username() : 'password';

        throw ValidationException::withMessages([
            $failedField => [$errorMessage],
        ]);
    }
}
