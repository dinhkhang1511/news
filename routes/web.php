<?php

use App\Http\Controllers\AuthConTroller;
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

Route::view('/', 'auth.login');
Route::view('/login', 'auth.login')->name('login');
Route::post('/login', [AuthConTroller::class,'checkLogin'])->name('checkLogin');

Route::view('/forget-password','auth.forget_password')->name('forget-password');
Route::view('/register','auth.register')->name('register');
Route::post('/register', [AuthConTroller::class,'register'])->name('checkRegister');


Route::prefix('admin')->group(function () {
    Route::view('/news', 'admin.news')->name('news');
});

