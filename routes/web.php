<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\destinationManagementController;
use App\Http\Controllers\goodsManagementController;
use App\Http\Controllers\memberManagementController;
use App\Http\Controllers\orderManagementController;
use App\Http\Controllers\orderRequestController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (Auth::user()) {
        return redirect('/orders');
    } else {
        return view('auth/login');
    }
});

Auth::routes();

// Route::post('/members/newmember', [App\Http\Controllers\manageMembersController::class, 'createNewMember'])->name('newMember');

Route::get('/goods/download/{id}', [goodsManagementController::class, 'download'])->name('goodsDownload');
Route::post('/goods/upload/{id}', [goodsManagementController::class, 'upload'])->name('goodsupload');
Route::get('/destination/changeRowNumber/{id}', [destinationManagementController::class, 'changeRowNumber'])->name('changeRowNumber');
Route::get('/edit-member-infor', [memberManagementController::class, 'editMemberInfor'])->name('editMemberInfor');
Route::get('/orders/download/{id}', [orderManagementController::class, 'ordersDownload'])->name('ordersDownload');
Route::get('/ordersd/orderRequest/download/', [orderManagementController::class, 'ordersRequestDownload'])->name('ordersRequestDownload');
Route::post('orders/orderRequest/upload', [orderManagementController::class, 'orderRequestUpload'])->name('orderRequestUpload');
Route::get('/orders/{user_id}/{order_id}', [orderManagementController::class, 'showDetailOrder'])->name('showDetailOrder');
Route::get('/orders/search', [orderManagementController::class, 'searchResult'])->name('searchResult');
Route::get('/orders/createNewOrder', [orderManagementController::class, 'createNewOrder'])->name('createNewOrder');

Route::resource('/members', memberManagementController::class);
Route::resource('/orders', orderManagementController::class);
Route::resource('/goods', goodsManagementController::class);
Route::resource('/destination', destinationManagementController::class);
Route::resource('/orderRequest', orderRequestController::class);


Route::get('/sendemail', [App\Http\Controllers\orderManagementController::class, 'sendingMail'])->name('sendingMail');
