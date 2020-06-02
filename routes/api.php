<?php

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::get('/scarape','ScrapeJsonController@soalsatu');
Route::get('/scrapexml','ScrapeJsonController@soaldua');
Route::get('/scrapexmllanjutan','ScrapeJsonController@soaltiga');

Route::get('/scrapesoal4','ScrapeJsonController@soalempat');
Route::get('/test','ScrapeJsonController@test');