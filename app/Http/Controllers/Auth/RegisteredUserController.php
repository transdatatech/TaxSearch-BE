<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PaymentCustomer;
use App\Models\PaymentMode;
use App\Models\User;
use App\Traits\StripeCustomerTrait;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Symfony\Component\HttpFoundation\Response;


class RegisteredUserController extends Controller
{
    use StripeCustomerTrait;
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): Response
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone'=>['required'],
            'mobile'=>['required'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        $user_details= [
            'first_name' => ucfirst($request->first_name),
            'last_name' => ucfirst($request->last_name),
            'phone_no'=>$request->phone,
            'mobile_no'=>$request->mobile,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_profile_completed'=>false,
        ];
        $user = User::create($user_details);
        $user->addRole('client');
        event(new Registered($user));
        $paymentMode = PaymentMode::select('id')->where(['name' => 'stripe', 'status' => true])->get()->toArray();
        if(!empty($paymentMode)){
            $paymentModeId = $paymentMode[0]['id'];
            $addUserToStripe=$this->createCustomer($user_details);
            $message="";
            if(!empty($addUserToStripe)){
                PaymentCustomer::create([
                    'user_id'=>$user->id,
                    'payment_mode_id'=>$paymentModeId,
                    'customer_id'=>$addUserToStripe->id
            ]);
                $message="and Stripe as customer";
            }
        }
        return response()->json([
            'status'=>true,
            'message' => 'You have registered Successfully on portal '.$message.', Check you mail to verify the email address'], 200);
    }
}
