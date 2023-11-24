<?php

namespace App\Http\Controllers;

use App\Models\FileBatch;
use App\Models\FileStatus;
use App\Models\PropertyFile;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
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
        try {
            $validator = Validator::make($request->all(), [
                'files.*' => 'required|mimes:csv,txt',
            ]);

            if ($validator->fails()) {
             return  setErrorResponse('Validation errors',$validator->errors()->messages());
            }
            if ($request->hasFile('files')) {
                $uploadedFileData = [];
                $statusId = FileStatus::where('name', 'Pending')->firstOrFail()->id;
                $fileBatch = FileBatch::create([
                    'name' => generateRandomString(),
                    'user_id' => $request->user()->id,
                ]);
                $uploadedFilesCount = 0;
                foreach ($request->file('files') as $file) {
                    $filePath = $file->store('public');

                    $propertyFile = PropertyFile::create([
                        'name' => $file->getClientOriginalName(),
                        'batch_id' => $fileBatch->id,
                        'status_id' => $statusId,
                        'path' => $filePath,
                    ]);

                    $fileData = csvToArray($file->getPathName());
                    $fileData = array_map(function ($arr) use ($propertyFile) {
                        return $arr + ['file_id' => $propertyFile->id];
                    }, $fileData);

                    $uploadedFileData = array_merge($uploadedFileData, $fileData);
                    $uploadedFilesCount++;
                }
                $message = ($uploadedFilesCount === count($request->file('files')))
                    ? 'Files uploaded successfully'
                    : 'Partially files have been uploaded';
                $responseData=[
                    'uploaded_files_count' => $uploadedFilesCount,
                    'batch_no' => $fileBatch->name,
                    'batch_id' => $fileBatch->id,
                    'file_data' => $uploadedFileData,
                ];
                return setSuccessResponse($message,$responseData);
            }
            return  setErrorResponse('No files were uploaded');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return  setErrorResponse('Something went wrong on Server!!');
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
