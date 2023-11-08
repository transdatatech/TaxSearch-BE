<?php

use App\Http\Controllers\PropertyFileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth:sanctum'])->resource('property_files',PropertyFileController::class);
