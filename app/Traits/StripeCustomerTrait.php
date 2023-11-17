<?php

namespace App\Traits;

use Stripe\StripeClient;
use Stripe\Exception\ApiErrorException;

trait StripeCustomerTrait
{
    public function createCustomer($customer_details)
    {
        try {
            $checkCustomer = $this->searchCustomer($customer_details['email']);
            if (empty($checkCustomer['data'])) {
                $stripeCustomer = [
                    'name' => ucwords($customer_details['first_name']) . " " . ucwords($customer_details['last_name']),
                    'email' => trim($customer_details['email']),
                    'phone' => isset($customer_details['phone_no']) ? $customer_details['phone_no'] : null
                ];
                $customer = new StripeClient(config('services.stripe.secret'));
                $customer = $customer->customers->create($stripeCustomer);
                if (is_object($customer)) {
                    return $customer;
                }else{
                   return [];
                }
            } else {
                return $checkCustomer['data'][0]->id;
            }
        } catch (ApiErrorException $e) {
            return [];
        }

    }

    public function getCustomerByID($customer_id)
    {
        try {
            $customer = new StripeClient(config('services.stripe.secret'));
            $customer = $customer->customers->retrieve($customer_id);
            if (is_object($customer)) {
                return $customer;
            } else {
                return [];
            }
        } catch (ApiErrorException $e) {
            return [];
        }
    }

    public function searchCustomer($customer_email)
    {
        try {
            $customer = new StripeClient(config('services.stripe.secret'));
            return $customer->customers->search([
                "query" => "email:'" . trim($customer_email) . "'",
            ]);
        } catch (ApiErrorException $e) {
            return [];
        }
    }

    public function attachCustomerDefaultPaymentMethod($customer_id,$attached_payment_id)
    {
        try {
            $customer = new StripeClient(config('services.stripe.secret'));
            return $customer->customers->update($customer_id, [
                'invoice_settings' => [
                    'default_payment_method' => $attached_payment_id,
                ]
            ]);
        } catch (ApiErrorException $e) {
            return [];
        }
    }

}
