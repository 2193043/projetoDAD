<?php

use Illuminate\Http\Request;

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
use App\Users;
use App\Wallets;
use App\Movements;

//autenticação
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('login', 'LoginControllerAPI@login'); 
Route::middleware('auth:api')->post('logout', 'LoginControllerAPI@logout');
Route::middleware('auth:api')->get('users/me', 'UserControllerAPI@myProfile');
Route::middleware('auth:api')->put('users/password/{id}','UserControllerAPI@changePassword');

//users
Route::apiResource('/users', 'UserControllerAPI');

Route::get('/verifyemail/{email}', 'UserControllerAPI@verifyEmail');
Route::get('/allOperators', 'UserControllerAPI@allOperators');
Route::get('/allAdmins', 'UserControllerAPI@allAdmins');
Route::get('/allPlatformUsers', 'UserControllerAPI@allPlatformUsers');
Route::get('/searchByName/{name}', 'UserControllerAPI@searchByName');
Route::get('/searchByEmail/{email}', 'UserControllerAPI@searchByEmail');
Route::get('/totalUsers', 'UserControllerAPI@totalUsers');
Route::get('/totalOperators', 'UserControllerAPI@totalOperators');
Route::get('/totalPlatformUsers', 'UserControllerAPI@totalPlatformUsers');
Route::get('/totalAdmins', 'UserControllerAPI@totalAdmins');

Route::post('/registeradmin', 'UserControllerAPI@newAdmin');
Route::post('/registeroperator', 'UserControllerAPI@newOperator');

Route::put('/deactivateUser/{id}', 'UserControllerAPI@deactivateUser');
Route::put('/reactivateUser/{id}', 'UserControllerAPI@reactivateUser');

//wallets
Route::apiResource('/wallets', 'WalletsControllerAPI');

Route::get('/totalwallets', 'WalletsControllerAPI@totalWallets');
Route::get('/getwallet/{email}', 'WalletsControllerAPI@getWalletByEmail');
Route::get('/verifyemailwallet/{email}', 'WalletsControllerAPI@verifyEmailWallet');
Route::get('/getcurrentbalance/{id}', 'WalletsControllerAPI@getCurrentBalance');

//movements
Route::apiResource('/movements', 'MovementsControllerAPI');

Route::get('/getmovementbywalletid/{id}', 'MovementsControllerAPI@getMovementByWalletId');
Route::get('/totalexpensesmovements/{id}', 'MovementsControllerAPI@totalExpensesMovements');
Route::get('/totalexpenses/{id}', 'MovementsControllerAPI@totalExpenses');
Route::get('/totalincomemovements/{id}', 'MovementsControllerAPI@totalIncomeMovements');
Route::get('/totalincome/{id}', 'MovementsControllerAPI@totalIncome');
Route::get('/searchByCategory/{idWallet}/{idCategory}', 'MovementsControllerAPI@searchByCategory');
Route::get('/searchByTypeMovement/{idWallet}/{typeMovement}', 'MovementsControllerAPI@searchByTypeMovement');
Route::get('/searchByTypeMovementAndCategory/{idWallet}/{typeMovement}/{idCategory}', 'MovementsControllerAPI@searchByTypeMovementAndCategory');
Route::get('/searchByTypePayment/{idWallet}/{typePayment}', 'MovementsControllerAPI@searchByTypePayment');
Route::get('/searchByTypePaymentAndCategory/{idWallet}/{typePayment}/{idCategory}', 'MovementsControllerAPI@searchByTypePaymentAndCategory');
Route::get('/searchByTypePaymentAndTypeMovement/{idWallet}/{typePayment}/{typeMovement}', 'MovementsControllerAPI@searchByTypePaymentAndTypeMovement');
Route::get('/searchByTypePaymentAndCategoryAndTypeMovement/{idWallet}/{typePayment}/{idCategory}/{typeMovement}', 'MovementsControllerAPI@searchByTypePaymentAndCategoryAndTypeMovement');
Route::get('/searchByEmailTransfer/{idWallet}/{emailTransfer}', 'MovementsControllerAPI@searchByEmailTransfer');
Route::get('/searchByEmailTransferAndCategory/{idWallet}/{emailTransfer}/{idCategory}', 'MovementsControllerAPI@searchByEmailTransferAndCategory');
Route::get('/searchByEmailTransferAndTypeMovement/{idWallet}/{emailTransfer}/{typeMovement}', 'MovementsControllerAPI@searchByEmailTransferAndTypeMovement');
Route::get('/searchByEmailTransferAndCategoryAndTypeMovement/{idWallet}/{emailTransfer}/{idCategory}/{typeMovement}', 'MovementsControllerAPI@searchByEmailTransferAndCategoryAndTypeMovement');
Route::get('/searchByDate/{idWallet}/{fromYear}-{fromMonth}-{fromDay}/{toYear}-{toMonth}-{toDay}', 'MovementsControllerAPI@searchByDate');
Route::get('/searchByDateAndCategory/{idWallet}/{fromYear}-{fromMonth}-{fromDay}/{toYear}-{toMonth}-{toDay}/{idCategory}', 'MovementsControllerAPI@searchByDateAndCategory');
Route::get('/searchByDateAndTypeMovement/{idWallet}/{fromYear}-{fromMonth}-{fromDay}/{toYear}-{toMonth}-{toDay}/{typeMovement}', 'MovementsControllerAPI@searchByDateAndTypeMovement');
Route::get('/searchByDateAndTypePayment/{idWallet}/{fromYear}-{fromMonth}-{fromDay}/{toYear}-{toMonth}-{toDay}/{typePayment}', 'MovementsControllerAPI@searchByDateAndTypePayment');
Route::get('/searchByDateAndEmailTransfer/{idWallet}/{fromYear}-{fromMonth}-{fromDay}/{toYear}-{toMonth}-{toDay}/{emailTransfer}', 'MovementsControllerAPI@searchByDateAndEmailTransfer');
Route::get('/searchByDateAndCategoryAndTypeMovement/{idWallet}/{fromYear}-{fromMonth}-{fromDay}/{toYear}-{toMonth}-{toDay}/{idCategory}/{typeMovement}', 'MovementsControllerAPI@searchByDateAndCategoryAndTypeMovement');
Route::get('/searchByDateAndCategoryAndTypePayment/{idWallet}/{fromYear}-{fromMonth}-{fromDay}/{toYear}-{toMonth}-{toDay}/{idCategory}/{typePayment}', 'MovementsControllerAPI@searchByDateAndCategoryAndTypePayment');
Route::get('/searchByDateAndCategoryAndEmailTransfer/{idWallet}/{fromYear}-{fromMonth}-{fromDay}/{toYear}-{toMonth}-{toDay}/{idCategory}/{emailTransfer}', 'MovementsControllerAPI@searchByDateAndCategoryAndEmailTransfer');
Route::get('/searchByDateAndCategoryAndTypeMovementAndTypePayment/{idWallet}/{fromYear}-{fromMonth}-{fromDay}/{toYear}-{toMonth}-{toDay}/{idCategory}/{typeMovement}/{typePayment}', 'MovementsControllerAPI@searchByDateAndCategoryAndTypeMovementAndTypePayment');
Route::get('/searchByDateAndCategoryAndTypeMovementAndEmailTransfer/{idWallet}/{fromYear}-{fromMonth}-{fromDay}/{toYear}-{toMonth}-{toDay}/{idCategory}/{typeMovement}/{emailTransfer}', 'MovementsControllerAPI@searchByDateAndCategoryAndTypeMovementAndEmailTransfer');
Route::get('/searchByDateAndTypeMovementAndTypePayment/{idWallet}/{fromYear}-{fromMonth}-{fromDay}/{toYear}-{toMonth}-{toDay}/{typeMovement}/{typePayment}', 'MovementsControllerAPI@searchByDateAndTypeMovementAndTypePayment');
Route::get('/searchByDateAndTypeMovementAndEmailTransfer/{idWallet}/{fromYear}-{fromMonth}-{fromDay}/{toYear}-{toMonth}-{toDay}/{typeMovement}/{emailTransfer}', 'MovementsControllerAPI@searchByDateAndTypeMovementAndEmailTransfer');

Route::post('/incomemovement', 'MovementsControllerAPI@incomeMovement');
Route::post('/incomemovementtransfer', 'MovementsControllerAPI@incomeMovementTransfer');
Route::post('/externalpaymentmb', 'MovementsControllerAPI@externalPaymentTypeMB');
Route::post('/externalpaymentbt', 'MovementsControllerAPI@externalPaymentTypeBT');
Route::post('/transferpayment', 'MovementsControllerAPI@transferPayment');

//categories
Route::get('/getexpensecategory', 'CategoriesControllerAPI@getExpenseCategory');
Route::get('/getIncomeCategory', 'CategoriesControllerAPI@getIncomeCategory');
Route::get('/getAllCategories', 'CategoriesControllerAPI@getAllCategories');





