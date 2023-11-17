<?php

namespace App\Traits;

use Stripe\Stripe;
use Stripe\Customer;

trait  StripePaymentTrait
{
    //initiate the stripe client
    public function initStripe()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    //Payment Intents
}
