<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TweetsController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
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

Route::get('/', [HomeController::class, 'index'])->name('home')->middleware('auth');
// authentication
Auth::routes();

// home
Route::get('/home', [HomeController::class, 'index'])->name('home');

// tweets
Route::post('/tweets_submit',[TweetsController::class,'store'])->name('tweets_submit')->middleware('auth');
Route::get('/tweets_delete',[TweetsController::class,'delete'])->name('tweets_delete')->middleware('auth');
Route::get('/tweets_get/{id}',[TweetsController::class,'get'])->name('tweets_get')->middleware('auth');
Route::post('/tweets_update',[TweetsController::class,'update'])->name('tweets_update')->middleware('auth');

// komentar
Route::POST('/komentar',[CommentController::class,'store'])->name('komentar')->middleware('auth');
Route::get('/komentar_delete',[CommentController::class,'delete'])->name('komentar_delete')->middleware('auth');
Route::get('/komentar_get/{id}',[CommentController::class,'get'])->name('komentar_get')->middleware('auth');
Route::POST('/komentar_update',[CommentController::class,'update'])->name('komentar_update')->middleware('auth');

// profile
Route::post('/editProfile',[ProfileController::class,'update'])->name('editProfile')->middleware('auth');

// filter
Route::post('filter',[HomeController::class,'filter'])->name('filter')->middleware('auth');
