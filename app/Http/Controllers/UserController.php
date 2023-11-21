<?php

namespace App\Http\Controllers;
use App\Models\State;
use App\Models\User;
use App\Models\UserState;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
//
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
                'mobile'=>'required',
            ]);
            if ($validator->fails()) {
                return setErrorResponse("Validation error's", $validator->errors()->messages());
            }
            $updateData=[
                'first_name' => ucfirst($request->first_name),
                'last_name' => ucfirst($request->last_name),
                'phone_no'=>$request->phone,
                'mobile_no'=>$request->mobile,
            ];
            $updateUserProfile=User::where('id',$id)->update($updateData);
            if($updateUserProfile){
               return setSuccessResponse('User profile updated successfully',[]);
            }else{
                return setErrorResponse('User profile not updated', []);
            }

        }catch (\Exception $e) {
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
     * Use with state discounted price.
     */

    public function user_states(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'state_id' => 'required',
                'price' => 'required|decimal:2'
            ]);
            if ($validator->fails()) {
                return setErrorResponse("Validation error's", $validator->errors()->messages());
            }
            $state=State::find($request->state_id);
            $user=User::find($request->user_id);
            if($state!=null && $user!=null ){
                $userStateData = [
                    'price'=>$request->price,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now(),
                ];
                if($state->price>=$request->price){
                    $checkStateUser=$user->states()->wherePivot('state_id',$state->id)->exists();
                    if(!$checkStateUser){
                        $user->states()->attach($state->id,$userStateData);
                        return setSuccessResponse('User State with price added successfully', []);
                    }else{
                        return setErrorResponse('User State with price already exists', []);
                    }
                }else{
                    return setErrorResponse('User State with price should be less than '.number_format($state->price,2), []);
                }
            }else{
                return setErrorResponse('Given state id not exists', []);
            }

        } catch (\Exception $e) {
            return setErrorResponse('Something went wrong on server !!', []);
        }
    }

}
