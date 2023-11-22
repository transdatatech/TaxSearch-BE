<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\State;
use App\Models\User;
use App\Models\UserState;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $users = User::all();
            if (!$users->isEmpty()) {
                return setSuccessResponse('Users retrieved successfully', UserResource::collection($users));
            } else {
                return setErrorResponse('No users found', []);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return setErrorResponse('Something went wrong on server !!', []);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'phone' => 'required',
                'mobile' => 'required',
            ]);
            if ($validator->fails()) {
                return setErrorResponse("Validation error's", $validator->errors()->messages());
            }
            $updateData = [
                'first_name' => ucfirst($request->first_name),
                'last_name' => ucfirst($request->last_name),
                'phone_no' => $request->phone,
                'mobile_no' => $request->mobile,
            ];
            $updateUserProfile = User::where('id', $id)->update($updateData);
            if ($updateUserProfile) {
                return setSuccessResponse('User profile updated successfully', []);
            } else {
                return setErrorResponse('User profile not updated', []);
            }

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return setErrorResponse('Something went wrong on server !!', []);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * User with state discounted price.
     */

    public function user_states(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'state_id' => 'required',
                'price' => 'required|decimal:2'
            ]);
            if ($validator->fails()) {
                return setErrorResponse("Validation error's", $validator->errors()->messages());
            }
            $state = State::find($request->state_id);
            $user = User::find($request->user_id);
            if (!is_null($state) && !is_null($user)) {
                $userStateData = [
                    'price' => $request->price,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
                if ($state->price >= $request->price) {
                    $checkStateUser = $user->states()->wherePivot('state_id', $state->id)->exists();
                    if (!$checkStateUser) {
                        $user->states()->attach($state->id, $userStateData);
                        return setSuccessResponse('User State with price added successfully', []);
                    } else {
                        return setErrorResponse('User State with price already exists', []);
                    }
                } else {
                    return setErrorResponse('User State with price should be less than ' . number_format($state->price, 2), []);
                }
            } else {
                return setErrorResponse('Given state id not exists', []);
            }

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return setErrorResponse('Something went wrong on server !!', []);
        }
    }

    /**
     * Send email verification email
     */
    public function send_verification_email_notification(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer',
            ]);
            if ($validator->fails()) {
                return setErrorResponse("Validation error's", $validator->errors()->messages());
            }
            $user = User::find($request->user_id);
            if (!is_null($user)) {
                if (is_null($user->email_verified_at)) {
                    $user->sendEmailVerificationNotification();
                    return setSuccessResponse('Email verification sent successfully', []);
                } else {
                    return setErrorResponse('User is already verified', []);
                }
            } else {
                return setErrorResponse('User not found', []);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return setErrorResponse('Something went wrong on server!!', []);
        }
    }

    /**
     * Active and in-active the user
     */

    public function active_inactive_user(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer',
            ]);
            if ($validator->fails()) {
                return setErrorResponse("Validation error's", $validator->errors()->messages());
            }
            $user = User::find($request->user_id);
            if (!is_null($user)) {
                if ($user->is_active) {
                    //In Active the user
                    User::where('id', $user->id)->update(['is_active' => false]);
                    return setSuccessResponse('User In active successfully', []);
                } else {
                    //Active the user
                    User::where('id', $user->id)->update(['is_active' => true]);
                    return setSuccessResponse('User active successfully', []);
                }
            } else {
                return setErrorResponse('User not found', []);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            echo $e->getMessage();
            return setErrorResponse('Something went wrong on server!!', []);
        }
    }

}
