<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(Request $request): RedirectResponse
    {

        $user=User::find($request->segment(3));
        if(!is_null($user)){
            if ($user->hasVerifiedEmail()) {
                return redirect()->intended(
                    config('app.frontend_url').'/verified'
                );
            }
            if ($user->markEmailAsVerified()) {
                event(new Verified($request->user()));
            }
            return redirect()->intended(
                config('app.frontend_url').'/verified'
            );
        }else{
            return redirect()->intended(
                config('app.frontend_url').'/invalid-verification'
            );
        }

    }
}
