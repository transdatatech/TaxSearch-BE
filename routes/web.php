<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PropertyFileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::get('/dashboard',function(){
   echo json_encode(['msg'=>'User Logged In Successfully']);
});

Route::middleware(['auth:sanctum'])->resource('property_files',PropertyFileController::class);

require __DIR__.'/auth.php';
