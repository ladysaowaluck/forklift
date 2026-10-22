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

    public function login(Request $request)
    {
        try {
            $this->validateLogin($request);

            if (
                method_exists($this, 'hasTooManyLoginAttempts') &&
                $this->hasTooManyLoginAttempts($request)
            ) {
                $this->fireLockoutEvent($request);

                return $this->sendLockoutResponse($request);
            }

            if ($this->attemptLogin($request)) {
                if ($request->hasSession()) {
                    $request->session()->put('auth.password_confirmed_at', time());
                }

                return $this->sendLoginResponse($request);
            }

            $this->incrementLoginAttempts($request);

            return $this->sendFailedLoginResponse($request);
        } catch (\Illuminate\Database\QueryException | \PDOException $e) {
            Log::error('[LoginController] Database error during login: ' . $e->getMessage());

            $request->session()->flash('login_error_type', 'db_error');
            $request->session()->flash('login_error_message', 'Unable to connect to database service. Please try again later.');

            throw ValidationException::withMessages([
                $this->username() => ['Unable to connect to database service. Please try again later.'],
            ]);
        }
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        $email = $request->input($this->username());

        $userExists = false;
        try {
            $userExists = User::where('email', $email)->exists();
        } catch (\Throwable $e) {
            Log::error('[LoginController] Database check error: ' . $e->getMessage());
            $userExists = true; // Fallback to generic message without exposing DB error
        }

        if (!$userExists) {
            $errorType = 'user_not_found';
            $errorMessage = "Email doesn't exist";
        } else {
            // Do not clarify whether email or password went wrong
            $errorType = 'auth_failed';
            $errorMessage = 'Email or password incorrect';
        }

        $request->session()->flash('login_error_type', $errorType);
        $request->session()->flash('login_error_message', $errorMessage);

        throw ValidationException::withMessages([
            $this->username() => [$errorMessage],
        ]);
    }
}
