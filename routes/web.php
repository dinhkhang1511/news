<?php

use App\Http\Controllers\AuthConTroller;
use App\Http\Controllers\CrawlController;
use App\Http\Controllers\PostController;
use Goutte\Client;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\Http;
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

Route::get('/', [AuthConTroller::class,'login']);
Route::get('/login', [AuthConTroller::class,'login'])->name('login');
Route::post('/login', [AuthConTroller::class,'checkLogin'])->name('checkLogin');

// Route::view('/forget-password','auth.forget_password')->name('forget-password');
// Route::view('/register','auth.register')->name('register');
// Route::post('/register', [AuthConTroller::class,'register'])->name('checkRegister');



Route::get('home',[PostController::class,'home'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('logout', [AuthConTroller::class,'logout'])->name('logout');


    Route::middleware('role')->prefix('admin')-> group( function() {
        Route::any('/', [PostController::class,'index']);
        Route::resource('post', PostController::class);
        Route::post('/publishing',[PostController::class,'publishing'])->name('publishing');

        // crawl data
        Route::get('/crawl', [CrawlController::class,'crawl']);
        Route::get('/crawl-category', [CrawlController::class,'crawlCategories']);
    });
});



