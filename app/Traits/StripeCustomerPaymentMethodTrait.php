<?php
namespace App\Traits;

use Stripe\Stripe;
use App\Models\PaymentMode;
use Stripe\StripeClient;
use Stripe\Exception\ApiErrorException;
use App\Models\PaymentCustomer;
trait StripeCustomerPaymentMethodTrait
{
    public function attachPaymentMethod($payment_method_id,$customer_id)
    {
        try {
            $createPaymentMethod=new StripeClient(config('services.stripe.secret'));
            $createPaymentMethod= $createPaymentMethod->paymentMethods->attach($payment_method_id,[
                'customer'=>$customer_id,
            ]);
             if(is_object($createPaymentMethod)){
                 return  $createPaymentMethod;
             }else{
                 return  [];
             }
        }catch (ApiErrorException $e){
            return  [];
        }
    }

    public function detachPaymentMethod($payment_method_id,$customer_id){
        try {
            $deletePaymentMethod=new StripeClient(config('services.stripe.secret'));
            $deletePaymentMethod=$deletePaymentMethod->paymentMethods->detach($payment_method_id,[
                'customer'=>$customer_id,
            ]);
            if(is_object($deletePaymentMethod)){
                return  $deletePaymentMethod;
            }else{
                return  [];
            }
        }catch (ApiErrorException $e){
            return  [];
        }
    }

    public function getPaymentMethod($payment_method_id){
        try {
            $retrievePaymentMethod=new StripeClient(config('services.stripe.secret'));
            $retrievePaymentMethod=$retrievePaymentMethod->paymentMethods->retrieve($payment_method_id);
            if(is_object($retrievePaymentMethod)){
                return  $retrievePaymentMethod;
            }else{
                return  [];
            }
        }catch (ApiErrorException $e){
            return  [];
        }
    }

    public function listPaymentMethod($customer_id){
        try {
            $listPaymentMethod=new StripeClient(config('services.stripe.secret'));
            $listPaymentMethod=$listPaymentMethod->paymentMethods->all(['customer'=>$customer_id,'type' => 'card',]);
            if(is_object($listPaymentMethod)){
                return  $listPaymentMethod;
            }else{
                return  [];
            }
        }catch (ApiErrorException $e){
            return  [];
        }
    }

}
