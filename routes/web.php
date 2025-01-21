<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\LocationController;

use App\Http\Controllers\ReportController;
use App\Http\Controllers\BoxController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TransferController;

use App\Models\Product;
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

Auth::routes();


Route::get('/', [HomeController::class, 'index'])->name('dashboard');

Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});

Route::resources([
    'roles' => RoleController::class,
    'users' => UserController::class,
    'permissions' => PermissionController::class,
    'settings' => SettingController::class,
]);

Route::get('/branches', [BranchController::class, 'index']);
Route::get('/branches/create', [BranchController::class, 'create'])->name('branches.create');
Route::post('/store', [BranchController::class, 'store'])->name('branches.store');
Route::get('/edit/{id}', [BranchController::class, 'edit']);
Route::put('/update/{id}', [BranchController::class, 'update']);
Route::delete('/destroy/{id}', [BranchController::class, 'destroy']);




// Product_info
Route::get('/Product', [ProductController::class, 'index']);
Route::get('/ListProduct', [ProductController::class, 'list']);
Route::post('/product_store/box', [ProductController::class, 'boxstore']);
Route::post('/product_store/flower', [ProductController::class, 'flowerstore']);

Route::get('/NewBoxColor', [ProductController::class, 'newboxcolor']);
Route::get('/NewBoxType', [ProductController::class, 'newboxtype']);
Route::get('/NewFlowerColor', [ProductController::class, 'newflowercolor']);

Route::get('/Additional', [ProductController::class, 'additonal']);
Route::get('/Additional', [ProductController::class, 'additonal']);




Route::post('/update-product/{product_id}', [ProductController::class, 'updateProduct']);
Route::delete('/delete-product/{product_id}', [ProductController::class, 'deleteProduct']);
//Route::get('/sync-products', [SyncController::class, 'syncAllToProduct']);




// Additional Box
Route::get('/box_color', [BoxController::class, 'boxcolor']);
Route::get('/bcoloredit/{id}', [BoxController::class, 'bcoloredit']);
Route::post('/bcolorupdate/{id}', [BoxController::class, 'bcolorupdate']);
Route::delete('/bcolordestroy/{id}', [BoxController::class, 'bcolordestroy']);


// box type
Route::get('/box_type', [BoxController::class, 'boxtype']);
Route::get('/btypeedit/{id}', [BoxController::class, 'bcoloredit']);
Route::post('/btypeupdate/{id}', [BoxController::class, 'btypeupdate']);
Route::delete('/btypedestroy/{id}', [BoxController::class, 'btypedestroy']);



// flower color
Route::get('/flower_color', [BoxController::class, 'flowercolor']);
Route::get('/fcoloredit/{id}', [BoxController::class, 'fcoloredit']);
Route::post('/fcolorupdate/{id}', [BoxController::class, 'fcolorupdate']);
Route::delete('/fcolordestroy/{id}', [BoxController::class, 'fcolordestroy']);

//service


Route::get('/AddNewThings', [ServiceController::class, 'index']);
Route::get('/service', [ServiceController::class, 'service']);




Route::get('/addservice', [ServiceController::class, 'addservice']);
Route::get('/serviceedit/{service_id_uniq}', [ServiceController::class, 'serviceedit']);
Route::post('/serviceupdate/{service_id_uniq}', [ServiceController::class, 'serviceupdate']);
Route::delete('/servicedestroy/{service_id_uniq}', [ServiceController::class, 'servicedestroy']);










Route::post('/product.update/{product_id}',[ProductController::class, 'proupdate']);

Route::delete('/destroyproduct/{product_id}', [ProductController::class, 'destroyproduct']);




// purchase
Route::get('/purchase', [PurchaseController::class, 'index']);
Route::get('/ListPurchase', [PurchaseController::class, 'list']);

Route::post('/purchase-details', [PurchaseController::class, 'store']);
Route::post('/update-product', [PurchaseController::class, 'updateProductDetails']);
Route::post('/update-product-stock', [PurchaseController::class, 'updateProductStock']);
Route::post('/submit', [PurchaseController::class, 'submit']);



Route::post('/payments', [PurchaseController::class, 'payment']);

Route::post('/paymentpay/{purchase_id}', [PurchaseController::class, 'processPayment']);


Route::get('/details/{purchase_id}', [PurchaseController::class, 'getPaymentsByPurchaseId']);


//sell
Route::get('/addSell', [SellController::class, 'index']);

Route::post('/sell',[SellController::class,'sell_info']);



Route::get('/ListSell', [SellController::class, 'list']);
Route::post('/sellpayments',[SellController::class,'sell_pay']);
Route::get('/sales/details/{sale_id}', [SellController::class, 'getPaymentsBySaleId']);

Route::post('/sales/Repayment/{sale_id}', [SellController::class, 'processSalePayment']);




Route::get('/openlocate', [LocationController::class, 'open']);

Route::post('/locate', [LocationController::class, 'store']);


Route::post('/update_total',[SellController::class,'update_total']);

//

//transfer
Route::get('/transfer', [TransferController::class, 'index']);

Route::post('/trans', [TransferController::class, 'store'])->name('transfer.store');

Route::post('/transferPay',[TransferController::class,'Transpay']);


Route::get('/listtransfer', [TransferController::class, 'list']);
Route::get('/transdetails/{transfer_id}', [TransferController::class, 'getPaymentsByTransferId']);

Route::post('/additiontransferpay/{transfer_id}',[TransferController::class,'processTransPayment']);



//

Route::get('/viewpurchase', [ReportController::class, 'Purchaseindex']);
Route::get('/viewsell', [ReportController::class, 'Sellindex']);
Route::get('/viewtransfer', [ReportController::class, 'Transferindex']);
Route::get('/Reportdetails/{purchase_id}', [ReportController::class, 'getPaymentsByPurchaseId']);
Route::get('/sales/Redetails/{sale_id}', [ReportController::class, 'getPaymentsBySaleId']);
Route::get('/Retransdetails/{transfer_id}', [ReportController::class, 'getPaymentsByTransferId']);
