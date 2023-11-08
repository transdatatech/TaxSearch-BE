<?php

namespace App\Http\Controllers;

use App\Models\FileBatch;
use App\Models\FileStatus;
use App\Models\PropertyFile;
use Illuminate\Bus\Batch;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PropertyFileController extends Controller
{
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
        $validator = Validator::make($request->all(), [
            'files.*' => 'required|mimes:csv,txt',
        ]);
        if ($validator->fails()) {
            $response = [
                "status" => Response::HTTP_UNPROCESSABLE_ENTITY,
                "success" => false,
                'error' => true,
                "msg" => "validation error",
                "data" => $validator->errors()->messages(),
            ];
            return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            if ($request->hasFile('files')) {
                $uploaded_file_data = [];
                $files = $request->file('files');
                $status_id = FileStatus::select('id')->where('name', 'Pending')->first();
                $file_batch_no = generateRandomString();
                $file_batch = new FileBatch();
                $file_batch->name = generateRandomString();
                $file_batch->user_id = $request->user()->id;
                $file_batch->save();
                $file_batch_id = $file_batch->id;
                $i = 0;
                foreach ($files as $file) {

                    $file_path = $file->store('public');

                    $property_file = new PropertyFile();
                    $property_file->name = $file->getClientOriginalName();
                    $property_file->batch_id = $file_batch_id;
                    $property_file->status_id = $status_id->id;
                    $property_file->path = $file_path;
                    $property_file->save();

                    $file_data = csvToArray($file->getPathName());
                    $file_data = array_map(function ($arr) use ($property_file) {
                        return $arr + ['file_id' => $property_file->id];
                    }, $file_data);
                    array_push($uploaded_file_data, $file_data);
                    $i++;
                }
                if ($i == count($files)) {
                    return response()->json([
                        'status' => true,
                        'uploaded_files_count' => $i,
                        'message' => 'All files have been uploaded successfully',
                        'batch_no' => $file_batch_no,
                        'file_data' => $uploaded_file_data

                    ], Response::HTTP_OK);
                } elseif ($i < count($files)) {
                    return response()->json([
                        'status' => true,
                        'uploaded_files_count' => $i,
                        'message' => 'Partially file have been uploaded',
                        'batch_no' => $file_batch_no,
                        'file_data' => $uploaded_file_data

                    ], Response::HTTP_ACCEPTED);
                } else {
                    return response()->json([
                        'status' => false,
                        'uploaded_files_count' => $i,
                        'message' => 'No File have been uploaded successfully',
                        'batch_no' => $file_batch_no,
                        'file_data' => $uploaded_file_data

                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                }
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PropertyFile $propertyFile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PropertyFile $propertyFile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PropertyFile $propertyFile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PropertyFile $propertyFile)
    {
        //
    }
}
