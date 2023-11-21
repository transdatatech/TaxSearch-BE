<?php

namespace App\Http\Controllers;

use App\Http\Resources\StateResource;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $states = State::all();
            if (!$states->isEmpty()) {
                return setSuccessResponse("States with price retrieved successfully", StateResource::collection($states));
            } else {
                return setErrorResponse('States with price not found', []);
            }
        } catch (\Exception $e) {
            return setErrorResponse('Something went wrong on server !!', []);
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
                'name' => 'required',
                'code' => 'required',
                'price' => 'required|decimal:2'
            ]);
            if ($validator->fails()) {
                return setErrorResponse("Validation error's", $validator->errors()->messages());
            }
            $stateData = [
                'name' => ucwords($request->name),
                'code' => strtoupper($request->code),
                'price' => $request->price,
            ];
            $state = State::updateOrCreate(['name' => $request->name], $stateData);
            if ($state->wasRecentlyCreated) {
                return setSuccessResponse('State with price added successfully', StateResource::make($state));
            } else {
                return setSuccessResponse('State with price already exists and updated',StateResource::make($state));
            }
        } catch (\Exception $e) {
            return setErrorResponse('Something went wrong on server !!', []);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $state = State::where('id', $id)->first();
            if ($state != null) {
                return setSuccessResponse("State with price retrieved successfully", StateResource::make($state));
            } else {
                return setErrorResponse('State with price not found', []);
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            return setErrorResponse('Something went wrong on server !!', []);
        }

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
                'name' => 'required',
                'code' => 'required',
                'price' => 'required|decimal:2'
            ]);
            if ($validator->fails()) {
                return setErrorResponse("Validation error's", $validator->errors()->messages());
            }
            $checkState = State::where(['name' => ucwords($request->name)])->where('id', '!=', $id)->get()->toArray();
            if (empty($checkState)) {
                $stateData = [
                    'name' => ucwords($request->name),
                    'code' => strtoupper($request->code),
                    'price' => $request->price,
                ];
                $state = State::where(['id' => $id])->update($stateData);
                if ($state) {
                    return setSuccessResponse('State with price updated successfully', []);
                } else {
                    return setErrorResponse('State with price not updated', []);
                }
            } else {
                return setErrorResponse('State with price already exists', []);
            }

        } catch (\Exception $e) {
            echo $e->getMessage();
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

}
