<?php

use App\Http\Controllers\BlogsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [BlogsController::class, 'showHome']);

Route::post('/check-email', [UserController::class, 'checkEmail']);
Route::resource('/blog', BlogsController::class);
Route::get('/all-blogs', [BlogsController::class, 'index']);

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
