<?php

namespace App\Traits;

use Stripe\Stripe;
use App\Models\PaymentMode;
use Stripe\StripeClient;
use Stripe\Exception\ApiErrorException;
use App\Models\PaymentCustomer;

trait StripeSetupPaymentIntentTrait
{
    public function createSetupIntent($customer_id)
    {
        try {
            $customerPaymentIntent = new StripeClient(config('services.stripe.secret'));
            $customerPaymentIntent = $customerPaymentIntent->setupIntents->create([
                'customer' => $customer_id,
                'payment_method_types' => ['card']
            ]);
            if (is_object($customerPaymentIntent)) {
                return $customerPaymentIntent;
            } else {
                return [];
            }
        } catch (ApiErrorException $e) {
            echo $e->getMessage();
            return [];
        }
    }

    public function getSetupIntent($setup_intent_id){
        try {
            $customerPaymentIntent = new StripeClient(config('services.stripe.secret'));
            $customerPaymentIntent = $customerPaymentIntent->setupIntents->retrieve($setup_intent_id);
            if (is_object($customerPaymentIntent)) {
                return $customerPaymentIntent;
            } else {
                return [];
            }
        } catch (ApiErrorException $e) {
            echo $e->getMessage();
            return [];
        }
    }

}
