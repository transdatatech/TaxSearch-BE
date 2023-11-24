<?php

namespace App\Traits;

use Stripe\Stripe;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

trait  StripePaymentTrait
{
    //initiate the stripe client
    public function initStripe()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    //Create payment Intents
    public function createStripePaymentIntent($payment_intent_data)
    {
        try {
            $createPaymentIntent = new StripeClient(config('services.stripe.secret'));
            $createPaymentIntent = $createPaymentIntent->paymentIntents->create($payment_intent_data);
            if (is_object($createPaymentIntent)) {
                return $createPaymentIntent;
            } else {

                return [];
            }

        } catch (ApiErrorException $e) {
            return [];
        }
    }

    //Capture payment
    public function captureStripePayment($payment_intent_id, $capture_amount)
    {
        try {
            $createPaymentIntent = new StripeClient(config('services.stripe.secret'));
            $createPaymentIntent = $createPaymentIntent->paymentIntents->capture($payment_intent_id, [
                'amount_to_capture' => $capture_amount
            ]);
            if (is_object($createPaymentIntent)) {
                return $createPaymentIntent;
            } else {
                return [];
            }

        } catch (ApiErrorException $e) {
            return [];
        }
    }

    //Confirm Payment
    public function confirmStripePayment($payment_intent_id)
    {
        try {
            $createPaymentIntent = new StripeClient(config('services.stripe.secret'));
            $createPaymentIntent = $createPaymentIntent->paymentIntents->confirm($payment_intent_id);
            if (is_object($createPaymentIntent)) {
                return $createPaymentIntent;
            } else {
                return [];
            }

        } catch (ApiErrorException $e) {
            return [];
        }
    }


}
