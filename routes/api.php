<?php

use App\Http\Controllers\ContactController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/contact/create', [ContactController::class, 'store']);
Route::post('/contact/delete', [ContactController::class, 'delete']);
Route::post('/contact/show', [ContactController::class, 'show']);
Route::post('/contact/changeState', [ContactController::class, 'changeState']);
