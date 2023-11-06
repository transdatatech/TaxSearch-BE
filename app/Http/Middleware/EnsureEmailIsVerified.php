<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // if (! $request->user() ||
        //     ($request->user() instanceof MustVerifyEmail &&
        //     ! $request->user()->hasVerifiedEmail())) {
        //     return response()->json([
        //         'status'=>false,
        //         'message' => 'Your email address is not verified.'], 409);
        // }
        $user=User::where('email',trim($request->email))->first();

        Log::info($user);
        if(!empty($user)){
            if(!$user->hasVerifiedEmail()){
                return response()->json([
                            'status'=>false,
                            'message' => 'Your email address is not verified.'], 409);
            }
            return $next($request);
        }else{
               return response()->json([
                'status'=>false,
                'message' => 'User not exists.'], 404);
        } 
    }
}
