<?php

use App\Http\Controllers\PropertyFileController;
use App\Http\Controllers\PropertyFileDataController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\InvoicePaymentController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\UserController;

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
    $user = $request->user();
    if ($user->id > 0) {
        $role = $user->roles()->pluck('name');
        $userPaymentAccounts = $user->userPaymentAccount()->get();
        $userPaymentMethods = $user->userPaymentMethods()->get();
        return [
            'user' => $user,
            'role' => $role,
            'user_payment_accounts' => $userPaymentAccounts,
            'user_payment_methods' => $userPaymentMethods,
        ];
    } else {
        return [
            'user' => $user,
            'role' => [],
            'user_payment_accounts' => [],
            'user_payment_methods' => [],
        ];
    }

});

//Auth Api to test in Postman
Route::post('login_token', [AuthController::class, 'loginUser']);
Route::post('api_register', [RegisteredUserController::class, 'store']);
Route::middleware('auth:sanctum')->post('api_logout', [AuthController::class, 'logoutUser']);

//Tax search api routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::resource('property_files', PropertyFileController::class);
    Route::resource('property_files_data', PropertyFileDataController::class);
    Route::resource('payment_methods', PaymentMethodController::class);
    Route::post('create_payment_method_setup_intent', [PaymentMethodController::class, 'create_card_setup_intent']);
    Route::resource('invoice_payments', InvoicePaymentController::class);
    Route::resource('states', StateController::class);
    Route::resource('users',UserController::class);
    Route::post('user_states', [UserController::class,'user_states']);
});

