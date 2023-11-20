<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\invoicePayment;
use App\Traits\StripePaymentTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;

class InvoicePaymentController extends Controller
{
    use StripePaymentTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'invoice_id' => 'required',
                'amount' => 'required|decimal:2',
                'stripe_customer_id' => 'required',
                'payment_method_id' => 'required',
            ]);
            if ($validator->fails()) {
                return setErrorResponse('Validation errors', $validator->errors()->messages());
            }
            $invoiceWithDetails = Invoice::with('invoiceDetails')->where(['uuid' => $request->invoice_id])->get();
            if (!$invoiceWithDetails->isEmpty()) {
                $invoiceWithDetails = $invoiceWithDetails->toArray();
                $invoiceId = $invoiceWithDetails[0]['id'];
                $createPaymentIntentData = [
                    'amount' => $request->amount * 100,
                    'currency' => 'usd',
                    'customer' => $request->stripe_customer_id,
                    'automatic_payment_methods' => ['enabled' => true,'allow_redirects'=>"never"],
                    'payment_method_options' => ['card' => ['capture_method' => 'manual']],
                    'description' => 'Invoice # ' . $request->invoice_id,
                    'payment_method' => $request->payment_method_id,
                    "confirm" => false
                ];
                $paymentIntent = $this->createStripePaymentIntent($createPaymentIntentData);
                $capturePaymentIntent = $this->captureStripePayment($paymentIntent->id, $createPaymentIntentData['amount']);
                if (!empty($paymentIntent) && !empty($capturePaymentIntent)) {
                    $invoicePayment = [
                        'invoice_id' => $invoiceId,
                        'payment_intent' => $paymentIntent->id
                    ];
                    $invoicePayment = invoicePayment::create($invoicePayment);
                    return setSuccessResponse("Payment captured successfully");
                } else {
                    return setErrorResponse('Payment not captured successfully', []);
                }
            } else {
                return setErrorResponse('Invoice id is not valid', []);
            }

        } catch (\Exception $e) {

            print_r($e->getMessage()." ".$e->getLine());
            return setErrorResponse('Something went wrong on server!!', []);
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
}
