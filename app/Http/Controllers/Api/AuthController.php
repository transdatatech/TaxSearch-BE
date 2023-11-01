<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class AuthController extends Controller
{
    /**
     * Register User
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerUser(Request $request)
    {
        Validator::validate($request->all(),
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required'
            ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        event(new Registered($user));

        return response()->json([
            'status' => true,
            'message' => 'User Created Successfully with verification email sent',
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ], Response::HTTP_OK);

    }

    /**
     * Login User
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function loginUser(Request $request)
    {
        Validator::validate($request->all(),
            [
                'email' => 'required|email',
                'password' => 'required'
            ]);
        if (!Auth::attempt($request->only(['email', 'password']))) {
            return response()->json([
                'status' => false,
                'message' => 'Email & Password does not match with our record.',
            ], Response::HTTP_UNAUTHORIZED);
        }
        $user = User::where('email', $request->email)->first();
        return response()->json([
            'status' => true,
            'message' => 'User Logged In Successfully',
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ], Response::HTTP_OK);
    }


    /**
     * Logout User
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function logoutUser(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => 'User Logout Successfully',
        ], Response::HTTP_OK);
    }

    public function sendVerificationEmail(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return [
                'message' => 'Already Verified'
            ];
        }
        $request->user()->sendEmailVerificationNotification();
        return ['status' => 'verification-link-sent'];
    }

    public function verify(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return [
                'message' => 'Email already verified'
            ];
        }
        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }
        return [
            'message' => 'Email has been verified'
        ];
    }
}
