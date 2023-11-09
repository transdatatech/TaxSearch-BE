<?php

use App\Http\Controllers\PropertyFileController;
use App\Http\Controllers\PropertyFileDataController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    $user=$request->user();
    if($user->id>0){
        $role=$user->roles()->pluck('name');
        return [
            'user'=>$user,
            'role'=>$role
        ];
    }else{
        return [
            'user'=>$user,
            'role'=>[],
        ];
    }

});

//Auth Api to test in Postman
Route::post('login',[AuthController::class,'loginUser']);
Route::middleware('auth:sanctum')->post('logout',[AuthController::class,'logoutUser']);

//Tax search api routes
Route::middleware(['auth:sanctum'])->group(function (){
    Route::resource('property_files',PropertyFileController::class);
    Route::resource('property_files_data',PropertyFileDataController::class);
});

