<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\BoxController;
use App\Http\Controllers\BranchController;

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

// Create route
Route::get('/branches/create', [BranchController::class, 'create'])->name('branches.create');

// Store route
Route::post('/store', [BranchController::class, 'store'])->name('branches.store');

// Edit route
Route::get('/edit/{id}', [BranchController::class, 'edit']);

// Update route
Route::put('/update/{id}', [BranchController::class, 'update']);

// Destroy route
Route::delete('/destroy/{id}', [BranchController::class, 'destroy']);




// Box_info
Route::get('/box_stock', [BoxController::class, 'index']);



Route::post('/box_store', [BoxController::class, 'box_store']);
//box_update
Route::put('/edit/{id}', [BoxController::class, 'edit']);

Route::put('/box_update/{id}', [BoxController::class, 'update']);
// /destroy_box
Route::delete('/destroy_box/{id}', [BoxController::class, 'destroy']);
