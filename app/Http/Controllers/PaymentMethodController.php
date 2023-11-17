<?php

namespace App\Http\Controllers;

use App\Models\CustomerPaymentMethod;
use App\Traits\StripeCustomerPaymentMethodTrait;
use App\Traits\StripeCustomerTrait;
use App\Traits\StripeSetupPaymentIntentTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentMethodController extends Controller
{
    use StripeSetupPaymentIntentTrait, StripeCustomerPaymentMethodTrait, StripeCustomerTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
           $user=auth()->user();
           $userPaymentAccounts = $user->userPaymentAccount()->get()->toArray();
           if(!empty($userPaymentAccounts)){
              $userPaymentMethods=$this->listPaymentMethod($userPaymentAccounts[0]['customer_id']);
              if(!empty($userPaymentMethods)){
                  $response=[
                      'user_payment_methods'=>$userPaymentMethods,
                  ];
                  return setSuccessResponse('Payment method retrieve successfully',$response);
              }else{
                  return setErrorResponse('No payment methods found',[]);
              }
           }else{
             return setErrorResponse('No payment methods found',[]);
           }
        }catch (\Exception $e){
            return setErrorResponse('Something went wrong on server!!',[]);
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
        try {
            $validator = Validator::make($request->all(), [
                'stripe_customer_id' => 'required',
                'user_payment_method_id' => 'required',
                'stripe_payment_method_id' => 'required',
                'is_default_payment_method' => 'required'
            ]);
            if ($validator->fails()) {
                return setErrorResponse('Validation errors', $validator->errors()->messages());
            }
            $customerId = $request->stripe_customer_id;
            $stripePaymentMethodId = $request->stripe_payment_method_id;
            $userPaymentMethodId = $request->user_payment_method_id;
            $customerPaymentMethodId = $this->attachPaymentMethod($stripePaymentMethodId, $customerId);
            if (!empty($customerPaymentMethodId)) {
                if ($request->is_default_payment_method) {
                    $this->attachCustomerDefaultPaymentMethod($customerId,$customerPaymentMethodId->id);
                }
                $payment_method = [
                    'created_payment_method_id' => $stripePaymentMethodId,
                    'attached_payment_method_id' => $customerPaymentMethodId->id,
                    'is_default_method' => ($request->is_default_payment_method)?true:false,
                    'status' => true,
                ];
                CustomerPaymentMethod::where('id', $userPaymentMethodId)->update($payment_method);
                $paymentMethodDetails = $this->getPaymentMethod($customerPaymentMethodId->id);
                $response = [
                    'payment_method_details' => $paymentMethodDetails,
                ];
                return setSuccessResponse("Payment Method added Successfully", $response);
            } else {
                return setErrorResponse("Payment Method not added Successfully ", []);
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            return setErrorResponse("Something went wrong on Server!!", []);
        }

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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * create Setup Intent to store User Payment Method
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function create_card_setup_intent(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_payment_customer_id' => 'required',
                'user_payment_account_id' => 'required'
            ]);
            if ($validator->fails()) {
                return setErrorResponse('Validation errors', $validator->errors()->messages());
            }
            $checkPaymentSetupIntentExists = CustomerPaymentMethod::select('id', 'payment_setup_intent')
                ->where(['user_id' => auth()->user()->id, 'customer_id' => $request->user_payment_account_id, 'status' => false])
                ->limit(1)
                ->get()->toArray();
            if (empty($checkPaymentSetupIntentExists)) {
                $userPaymentSetupIntent = $this->createSetupIntent($request->user_payment_customer_id);
                if (!empty($userPaymentSetupIntent)) {
                    $userPaymentMethod = [
                        'user_id' => auth()->user()->id,
                        'customer_id' => $request->user_payment_account_id,
                        'payment_setup_intent' => $userPaymentSetupIntent->id,
                        'status' => false,
                    ];
                    $paymentMethodId = CustomerPaymentMethod::create($userPaymentMethod);
                    $response = [
                        'customer_secret' => $userPaymentSetupIntent->client_secret,
                        'user_payment_account_id' => $request->user_payment_account_id,
                        'payment_method_id' => $paymentMethodId->id
                    ];
                    return setSuccessResponse('Stripe customer secret created', $response);
                } else {
                    return setErrorResponse('Stripe customer secret not created', []);
                }
            } else {
                $userPaymentSetupIntent = $this->getSetupIntent($checkPaymentSetupIntentExists[0]['payment_setup_intent']);
                if (!empty($userPaymentSetupIntent)) {
                    $response = [
                        'customer_secret' => $userPaymentSetupIntent->client_secret,
                        'user_payment_account_id' => $request->user_payment_customer_id,
                        'payment_method_id' => $checkPaymentSetupIntentExists[0]['id']
                    ];
                    return setSuccessResponse('Stripe customer secret created', $response);
                } else {
                    return setErrorResponse('Stripe customer secret not created', []);
                }
            }
        } catch (\Exception $e) {
            return setErrorResponse('Something went wrong on Server!!');
        }
    }

}
