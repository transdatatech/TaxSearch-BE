<?php

namespace App\Http\Controllers;

use App\Models\FileStatus;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\PropertyFileData;
use App\Models\State;
use App\Traits\StripeCustomerTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class PropertyFileDataController extends Controller
{
    use StripeCustomerTrait;
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
     */
    public function store(Request $request)
    {
        try {
            $fileData = $request->file_data;
            $batchId = $request->batch_id;
            $status = FileStatus::where('name', 'Processing')->firstOrFail();
            $discountPrice = 0.00;
            $totalPrice = 0.00;
            $taxAmount = 0.00;
            if (!empty($fileData)) {
                $validationErrors = [];
                $processedFileData = [];
                $unProcessedFileData = [];
                $invoiceDetailData = [];
                foreach ($fileData as $key => $data) {
                    $validator = Validator::make($data, [
                        'property_id' => 'required',
                        'area' => 'required',
                        'address' => 'required',
                        'state' => 'required',
                        'country' => 'required',
                    ]);
                    // Data validation
                    if ($validator->fails()) {
                        $validationError = [
                            $key => $validator->errors(),
                        ];
                        $validationErrors[] = $validationError;
                    }
                    //Processing the file data
                    $stateData = State::select('states.id', 'states.name', 'states.price')->with(['users' => function ($q) use ($request) {
                        $q->where('user_id', $request->user()->id);
                    }])->where('name', 'like', $data['state'])->get()->toArray();
                    if (!empty($stateData)) {
                        $propertyFileData = [
                            'file_id' => $data['file_id'],
                            'batch_id' => $batchId,
                            'state_id' => $stateData[0]['id'],
                            'property_id' => $data['property_id'],
                            'area' => $data['area'],
                            'address' => $data['address'],
                            'zip_code' => $data['zip_code'],
                            'country' => $data['country'],
                            'status_id' => $status->id,
                        ];
                        $statePrice = (!empty($stateData[0]['users'])) ? $stateData[0]['users'][0]['price'] : $stateData[0]['price'];
                        //Inserting property file data
                        $propertyFileDataId = PropertyFileData::create($propertyFileData);
                        $processedFileData[] = $propertyFileData;
                        $propertyFileData = array_map(function ($arr) use ($data) {
                            return $arr + ['state_name' => $data['state']];
                        }, $propertyFileData);
                        $invoiceDetails = [
                            'property_file_data_id' => $propertyFileDataId->id,
                            'data' => json_encode($propertyFileData),
                            'price' => $statePrice
                        ];
                        $invoiceDetailData[] = $invoiceDetails;
                        $discountPrice += (!empty($stateData[0]['users'])) ? $stateData[0]['price'] - $stateData[0]['users'][0]['price'] : 0.00;
                        $totalPrice += $statePrice;
                    } else {
                        $unProcessedFileData[] = $data;
                    }

                }
                //Generating invoice summary
                $invoiceSummary = [
                    'user_id' => $request->user()->id,
                    'discount' => $discountPrice,
                    'tax' => $taxAmount,
                    'total_amount' => $totalPrice
                ];
                $invoiceSummary = Invoice::create($invoiceSummary);
                $invoiceDetailData = array_map(function ($arr) use ($invoiceSummary) {
                    return $arr + ['invoice_id' => $invoiceSummary->id];
                }, $invoiceDetailData);
                //Generating invoice details
                InvoiceDetail::insert($invoiceDetailData);
                $processedFileData = array_map(function ($arr) use ($status) {
                    return $arr + ['status' => $status->name];
                }, $processedFileData);
                $responseData = [
                    'processed_file_data' => $processedFileData,
                    'unprocessed_file_data' => $unProcessedFileData,
                    'file_data_errors' => $validationErrors,
                    'invoice_details' => $invoiceDetailData,
                    'invoice_summary' => $invoiceSummary,
                ];
                return setSuccessResponse("File data added and invoice generated successfully", $responseData);
            }
            return setErrorResponse('File data required to for processing');
        } catch (\Exception $e) {
            return setErrorResponse('Something went wrong on Server!!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PropertyFileData $propertyFileData)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PropertyFileData $propertyFileData)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PropertyFileData $propertyFileData)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PropertyFileData $propertyFileData)
    {
        //
    }
}
